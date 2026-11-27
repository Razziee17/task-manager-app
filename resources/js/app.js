const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const taskTableBody = document.querySelector('#task-list');
const taskModal = document.querySelector('#task-modal');
const taskForm = document.querySelector('#task-form');
const taskModalTitle = document.querySelector('#task-modal-title');
const confirmationModal = document.querySelector('#task-confirmation-modal');
const confirmationTitle = document.querySelector('#task-confirmation-title');
const confirmationMessage = document.querySelector('#task-confirmation-message');
const confirmTaskActionButton = document.querySelector('#confirm-task-action');
const taskPageSize = 10;
let tasks = [];
let categories = [];
let currentPage = 1;
let pendingConfirmation = null;
let confirmationTrigger = null;

async function apiRequest(url, options = {}) {
    const response = await fetch(url, {
        credentials: 'same-origin',
        ...options,
        headers: {
            Accept: 'application/json',
            ...(options.body ? { 'Content-Type': 'application/json' } : {}),
            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            ...options.headers,
        },
    });
    const payload = response.status === 204 ? null : await response.json().catch(() => null);

    if (!response.ok) {
        const validationMessages = Object.values(payload?.errors ?? {}).flat();
        const message = validationMessages.length
            ? validationMessages.join(' ')
            : payload?.message ?? 'The request could not be completed.';

        throw new Error(message);
    }

    if (response.status !== 204 && !payload?.data) {
        throw new Error('The server returned an invalid response.');
    }

    return payload?.data ?? null;
}

function showApiError(elementId, message) {
    const error = document.querySelector(`#${elementId}`);
    if (!error) {
        return;
    }

    error.textContent = message;
    error.hidden = false;
}

function clearApiError(elementId) {
    const error = document.querySelector(`#${elementId}`);
    if (error) {
        error.textContent = '';
        error.hidden = true;
    }
}

function updateOverviewCounts() {
    const allCount = document.querySelector('#overview-all-count');
    const pendingCount = document.querySelector('#overview-pending-count');
    const doneCount = document.querySelector('#overview-done-count');

    if (allCount) {
        allCount.textContent = tasks.length;
    }

    if (pendingCount) {
        pendingCount.textContent = tasks.filter((task) => task.status === 'pending').length;
    }

    if (doneCount) {
        doneCount.textContent = tasks.filter((task) => task.status === 'done').length;
    }
}

function renderOverviewCategories() {
    const categoryList = document.querySelector('#overview-category-list');
    if (!categoryList) {
        return;
    }

    const categoryCount = document.querySelector('#overview-category-count');
    const emptyState = document.querySelector('#overview-category-empty');
    categoryList.replaceChildren();
    categoryCount.textContent = `${categories.length} ${categories.length === 1 ? 'category' : 'categories'}`;
    emptyState.hidden = categories.length !== 0;

    categories.forEach((category) => {
        const link = document.createElement('a');
        link.className = 'category-card';
        link.href = `${document.body.dataset.tasksUrl}?category=${encodeURIComponent(category.id)}`;

        const details = document.createElement('span');
        const name = document.createElement('strong');
        name.textContent = category.name;
        const taskCount = document.createElement('small');
        const count = category.tasks_count;
        taskCount.textContent = `${count} ${count === 1 ? 'task' : 'tasks'}`;
        details.append(name, taskCount);

        const arrow = document.createElement('span');
        arrow.setAttribute('aria-hidden', 'true');
        arrow.textContent = '→';
        link.append(details, arrow);
        categoryList.append(link);
    });
}

function populateCategoryOptions() {
    const categoryFilter = document.querySelector('#category-filter');
    const taskCategory = document.querySelector('#task-category');

    if (!categoryFilter || !taskCategory) {
        return;
    }

    const selectedCategory = new URLSearchParams(window.location.search).get('category');
    categoryFilter.replaceChildren(new Option('All categories', 'all'));
    taskCategory.replaceChildren(new Option('Select a category', ''));

    categories.forEach((category) => {
        categoryFilter.add(new Option(category.name, String(category.id)));
        taskCategory.add(new Option(category.name, String(category.id)));
    });

    if (categories.some((category) => String(category.id) === selectedCategory)) {
        categoryFilter.value = selectedCategory;
    }
}

function openTaskModal(task = null) {
    if (!taskModal || !taskForm) {
        return;
    }

    taskForm.reset();
    clearApiError('task-form-error');
    document.querySelector('#title-error').textContent = '';
    document.querySelector('#date-error').textContent = '';
    document.querySelector('#task-id').value = task?.id ?? '';
    document.querySelector('#task-title').value = task?.title ?? '';
    document.querySelector('#task-description').value = task?.description ?? '';
    document.querySelector('#task-category').value = task?.category_id ?? '';
    document.querySelector('#task-status').value = task?.status ?? 'pending';
    document.querySelector('#task-due-date').value = task?.due_date ?? '';
    taskModalTitle.textContent = task ? 'Edit task' : 'Add task';
    taskModal.hidden = false;
    document.querySelector('#task-title').focus();
}

function closeTaskModal() {
    if (taskModal) {
        taskModal.hidden = true;
    }
}

function openConfirmationModal(action, task) {
    const confirmations = {
        edit: {
            title: 'Edit task?',
            message: `Open "${task.title}" for editing?`,
            button: 'Continue',
            buttonClass: 'primary-button',
        },
        complete: {
            title: 'Mark task as done?',
            message: `Mark "${task.title}" as completed?`,
            button: 'Mark done',
            buttonClass: 'primary-button',
        },
        delete: {
            title: 'Delete task?',
            message: `Delete "${task.title}"? This cannot be undone.`,
            button: 'Delete',
            buttonClass: 'danger-button',
        },
    };
    const confirmation = confirmations[action];

    if (!confirmationModal || !confirmation) {
        return;
    }

    pendingConfirmation = { action, taskId: task.id };
    confirmationTrigger = document.activeElement instanceof HTMLElement ? document.activeElement : null;
    confirmationTitle.textContent = confirmation.title;
    confirmationMessage.textContent = confirmation.message;
    confirmTaskActionButton.textContent = confirmation.button;
    confirmTaskActionButton.className = confirmation.buttonClass;
    confirmationModal.hidden = false;
    document.querySelector('#cancel-task-confirmation').focus();
}

function closeConfirmationModal(restoreFocus = true) {
    if (!confirmationModal) {
        return;
    }

    confirmationModal.hidden = true;
    pendingConfirmation = null;

    const trigger = confirmationTrigger;
    confirmationTrigger = null;

    if (restoreFocus && trigger instanceof HTMLElement) {
        trigger.focus();
    }
}

async function confirmPendingAction() {
    if (!pendingConfirmation) {
        return;
    }

    const { action, taskId } = pendingConfirmation;
    const task = tasks.find((item) => item.id === taskId);
    closeConfirmationModal(false);

    if (!task) {
        await loadTasks();
        return;
    }

    if (action === 'edit') {
        openTaskModal(task);
        return;
    }

    try {
        clearApiError('task-api-error');

        if (action === 'complete') {
            const updatedTask = await apiRequest(`/api/tasks/${taskId}`, {
                method: 'PATCH',
                body: JSON.stringify({ status: 'done' }),
            });
            tasks = tasks.map((item) => item.id === updatedTask.id ? updatedTask : item);
        } else if (action === 'delete') {
            await apiRequest(`/api/tasks/${taskId}`, { method: 'DELETE' });
            tasks = tasks.filter((item) => item.id !== taskId);
        }

        renderTasks();
    } catch (error) {
        showApiError('task-api-error', error.message);
    }
}

function formatDate(dateString) {
    if (!dateString) {
        return 'No due date';
    }

    return new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    }).format(new Date(`${dateString}T00:00:00`));
}

function createActionButton(label, className, action, taskId) {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = className;
    button.textContent = label;
    button.dataset.action = action;
    button.dataset.taskId = taskId;
    return button;
}

function updatePagination(visibleTasks) {
    const pagination = document.querySelector('#task-pagination');
    if (!pagination) {
        return;
    }

    const pageCount = Math.max(1, Math.ceil(visibleTasks.length / taskPageSize));
    currentPage = Math.min(currentPage, pageCount);
    const firstItem = visibleTasks.length ? (currentPage - 1) * taskPageSize + 1 : 0;
    const lastItem = Math.min(currentPage * taskPageSize, visibleTasks.length);

    document.querySelector('#task-page-status').textContent =
        `${firstItem}-${lastItem} of ${visibleTasks.length}`;
    document.querySelector('#previous-task-page').disabled = currentPage === 1;
    document.querySelector('#next-task-page').disabled = currentPage === pageCount;
    pagination.hidden = visibleTasks.length <= taskPageSize;
}

function filterTasks() {
    if (!taskTableBody) {
        return;
    }

    const searchText = document.querySelector('#task-search')?.value.trim().toLowerCase() ?? '';
    const selectedCategory = document.querySelector('#category-filter')?.value ?? 'all';
    const selectedStatus = document.querySelector('#status-filter')?.value ?? 'all';
    const rows = [...taskTableBody.querySelectorAll('.task-row')];
    const visibleTasks = rows.filter((row) => {
        const matchesSearch = row.dataset.title.includes(searchText);
        const matchesCategory = selectedCategory === 'all' || row.dataset.category === selectedCategory;
        const matchesStatus = selectedStatus === 'all' || row.dataset.status === selectedStatus;

        return matchesSearch && matchesCategory && matchesStatus;
    });
    const firstVisibleIndex = (currentPage - 1) * taskPageSize;
    const visibleSet = new Set(visibleTasks.slice(firstVisibleIndex, firstVisibleIndex + taskPageSize));

    rows.forEach((row) => {
        row.hidden = !visibleSet.has(row);
    });

    document.querySelector('#no-filtered-results').hidden = tasks.length === 0 || visibleTasks.length !== 0;
    updatePagination(visibleTasks);
}

function renderTasks() {
    updateOverviewCounts();

    if (!taskTableBody) {
        return;
    }

    taskTableBody.replaceChildren();
    document.querySelector('#task-count').textContent = `${tasks.length} ${tasks.length === 1 ? 'item' : 'items'}`;
    document.querySelector('#empty-task-list').hidden = tasks.length !== 0;

    tasks.forEach((task) => {
        const row = document.createElement('tr');
        row.className = 'task-row';
        row.dataset.title = task.title.toLowerCase();
        row.dataset.category = String(task.category_id);
        row.dataset.status = task.status;

        const taskCell = document.createElement('td');
        const title = document.createElement('strong');
        title.className = 'task-title';
        title.textContent = task.title;
        taskCell.append(title);

        if (task.description) {
            const description = document.createElement('span');
            description.className = 'task-description';
            description.textContent = task.description;
            taskCell.append(description);
        }

        const categoryCell = document.createElement('td');
        const categoryBadge = document.createElement('span');
        categoryBadge.className = 'category-badge';
        categoryBadge.textContent = task.category.name;
        categoryCell.append(categoryBadge);

        const statusCell = document.createElement('td');
        const statusBadge = document.createElement('span');
        statusBadge.className = `status-badge status-${task.status}`;
        statusBadge.textContent = task.status === 'done' ? 'Completed' : 'Pending';
        statusCell.append(statusBadge);

        const dateCell = document.createElement('td');
        dateCell.className = 'due-date';
        dateCell.textContent = formatDate(task.due_date);

        const actionCell = document.createElement('td');
        actionCell.className = 'task-actions';
        const actionButtons = [
            createActionButton('Edit', 'table-action', 'edit', task.id),
        ];

        if (task.status !== 'done') {
            actionButtons.push(
                createActionButton('Done', 'table-action table-action-success', 'complete', task.id),
            );
        }

        actionButtons.push(
            createActionButton('Delete', 'table-action table-action-danger', 'delete', task.id),
        );
        actionCell.append(...actionButtons);

        row.append(taskCell, categoryCell, statusCell, dateCell, actionCell);
        taskTableBody.append(row);
    });

    filterTasks();
}

async function initializePage() {
    const results = await Promise.allSettled([
        apiRequest('/api/categories'),
        apiRequest('/api/tasks'),
    ]);

    if (results[0].status === 'fulfilled') {
        categories = results[0].value;
        populateCategoryOptions();
        renderOverviewCategories();
    } else {
        showApiError('task-api-error', results[0].reason.message);
        showApiError('overview-api-error', results[0].reason.message);
    }

    if (results[1].status === 'fulfilled') {
        tasks = results[1].value;
        renderTasks();
    } else {
        showApiError('task-api-error', results[1].reason.message);
        showApiError('overview-api-error', results[1].reason.message);
    }
}

async function submitTaskForm(event) {
    event.preventDefault();

    const titleInput = document.querySelector('#task-title');
    const dateInput = document.querySelector('#task-due-date');
    const titleError = document.querySelector('#title-error');
    const dateError = document.querySelector('#date-error');

    titleError.textContent = titleInput.value.trim() ? '' : 'Enter a task title.';
    dateError.textContent = dateInput.value ? '' : 'Choose a due date.';

    if (!titleInput.value.trim() || !dateInput.value) {
        return;
    }

    const taskId = document.querySelector('#task-id').value;
    const taskData = {
        title: titleInput.value.trim(),
        description: document.querySelector('#task-description').value.trim(),
        category_id: document.querySelector('#task-category').value,
        status: document.querySelector('#task-status').value,
        due_date: dateInput.value,
    };

    try {
        clearApiError('task-form-error');

        if (taskId) {
            const updatedTask = await apiRequest(`/api/tasks/${taskId}`, {
                method: 'PATCH',
                body: JSON.stringify(taskData),
            });
            tasks = tasks.map((task) => task.id === updatedTask.id ? updatedTask : task);
        } else {
            const createdTask = await apiRequest('/api/tasks', {
                method: 'POST',
                body: JSON.stringify(taskData),
            });
            tasks.unshift(createdTask);
        }

        currentPage = 1;
        closeTaskModal();
        renderTasks();
    } catch (error) {
        showApiError('task-form-error', error.message);
    }
}

document.querySelector('#open-task-modal')?.addEventListener('click', () => openTaskModal());
document.querySelector('#close-task-modal')?.addEventListener('click', closeTaskModal);
document.querySelector('#cancel-task')?.addEventListener('click', closeTaskModal);
document.querySelector('#task-modal')?.addEventListener('click', (event) => {
    if (event.target === event.currentTarget) {
        closeTaskModal();
    }
});

document.querySelector('#close-task-confirmation')?.addEventListener('click', () => closeConfirmationModal());
document.querySelector('#cancel-task-confirmation')?.addEventListener('click', () => closeConfirmationModal());
confirmTaskActionButton?.addEventListener('click', confirmPendingAction);
confirmationModal?.addEventListener('click', (event) => {
    if (event.target === event.currentTarget) {
        closeConfirmationModal();
    }
});

taskForm?.addEventListener('submit', submitTaskForm);

taskTableBody?.addEventListener('click', (event) => {
    const button = event.target.closest('[data-action]');
    if (!button) {
        return;
    }

    const task = tasks.find((item) => String(item.id) === button.dataset.taskId);
    if (task && ['edit', 'complete', 'delete'].includes(button.dataset.action)) {
        openConfirmationModal(button.dataset.action, task);
    }
});

document.querySelector('#task-search')?.addEventListener('input', () => {
    currentPage = 1;
    filterTasks();
});
document.querySelector('#category-filter')?.addEventListener('change', () => {
    currentPage = 1;
    filterTasks();
});
document.querySelector('#status-filter')?.addEventListener('change', () => {
    currentPage = 1;
    filterTasks();
});
document.querySelector('#previous-task-page')?.addEventListener('click', () => {
    currentPage = Math.max(1, currentPage - 1);
    filterTasks();
});
document.querySelector('#next-task-page')?.addEventListener('click', () => {
    currentPage += 1;
    filterTasks();
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && confirmationModal && !confirmationModal.hidden) {
        closeConfirmationModal();
        return;
    }

    if (event.key === 'Escape' && taskModal && !taskModal.hidden) {
        closeTaskModal();
    }
});

initializePage();
