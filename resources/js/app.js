const storageKey = 'taskManagerTasks';

function readTasks() {
    try {
        return JSON.parse(localStorage.getItem(storageKey)) ?? [];
    } catch {
        return [];
    }
}

function saveTasks(tasks) {
    localStorage.setItem(storageKey, JSON.stringify(tasks));
}

function updateOverviewCounts() {
    const tasks = readTasks();
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

const taskTableBody = document.querySelector('#task-list');
const taskModal = document.querySelector('#task-modal');
const taskForm = document.querySelector('#task-form');
const taskModalTitle = document.querySelector('#task-modal-title');

function openTaskModal(task = null) {
    if (!taskModal || !taskForm) {
        return;
    }

    taskForm.reset();
    document.querySelector('#title-error').textContent = '';
    document.querySelector('#date-error').textContent = '';
    document.querySelector('#task-id').value = task?.id ?? '';
    document.querySelector('#task-title').value = task?.title ?? '';
    document.querySelector('#task-description').value = task?.description ?? '';
    document.querySelector('#task-category').value = task?.category ?? document.querySelector('#task-category').options[0]?.value ?? '';
    document.querySelector('#task-status').value = task?.status ?? 'pending';
    document.querySelector('#task-due-date').value = task?.dueDate ?? '';
    taskModalTitle.textContent = task ? 'Edit task' : 'Add task';
    taskModal.hidden = false;
    document.querySelector('#task-title').focus();
}

function closeTaskModal() {
    if (taskModal) {
        taskModal.hidden = true;
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

function filterTasks() {
    if (!taskTableBody) {
        return;
    }

    const searchText = document.querySelector('#task-search')?.value.trim().toLowerCase() ?? '';
    const selectedCategory = document.querySelector('#category-filter')?.value ?? 'all';
    const selectedStatus = document.querySelector('#status-filter')?.value ?? 'all';
    const rows = taskTableBody.querySelectorAll('.task-row');
    let visibleCount = 0;

    rows.forEach((row) => {
        const matchesSearch = row.dataset.title.includes(searchText);
        const matchesCategory = selectedCategory === 'all' || row.dataset.category === selectedCategory;
        const matchesStatus = selectedStatus === 'all' || row.dataset.status === selectedStatus;
        const isVisible = matchesSearch && matchesCategory && matchesStatus;

        row.hidden = !isVisible;
        visibleCount += isVisible ? 1 : 0;
    });

    document.querySelector('#no-filtered-results').hidden = visibleCount !== 0;
}

function renderTasks() {
    if (!taskTableBody) {
        updateOverviewCounts();
        return;
    }

    const tasks = readTasks();
    taskTableBody.replaceChildren();
    document.querySelector('#task-count').textContent = `${tasks.length} ${tasks.length === 1 ? 'item' : 'items'}`;
    document.querySelector('#empty-task-list').hidden = tasks.length !== 0;

    tasks.forEach((task) => {
        const row = document.createElement('tr');
        row.className = 'task-row';
        row.dataset.title = task.title.toLowerCase();
        row.dataset.category = task.category;
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
        categoryBadge.textContent = task.category;
        categoryCell.append(categoryBadge);

        const statusCell = document.createElement('td');
        const statusBadge = document.createElement('span');
        statusBadge.className = `status-badge status-${task.status}`;
        statusBadge.textContent = task.status === 'done' ? 'Completed' : 'Pending';
        statusCell.append(statusBadge);

        const dateCell = document.createElement('td');
        dateCell.className = 'due-date';
        dateCell.textContent = formatDate(task.dueDate);

        const actionCell = document.createElement('td');
        actionCell.className = 'task-actions';
        actionCell.append(
            createActionButton('Edit', 'table-action', 'edit', task.id),
            createActionButton('Delete', 'table-action table-action-danger', 'delete', task.id),
        );

        row.append(taskCell, categoryCell, statusCell, dateCell, actionCell);
        taskTableBody.append(row);
    });

    filterTasks();
    updateOverviewCounts();
}

document.querySelector('#open-task-modal')?.addEventListener('click', () => openTaskModal());
document.querySelector('#close-task-modal')?.addEventListener('click', closeTaskModal);
document.querySelector('#cancel-task')?.addEventListener('click', closeTaskModal);
document.querySelector('#task-modal')?.addEventListener('click', (event) => {
    if (event.target === event.currentTarget) {
        closeTaskModal();
    }
});

taskForm?.addEventListener('submit', (event) => {
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

    const tasks = readTasks();
    const taskId = document.querySelector('#task-id').value;
    const task = {
        id: taskId || crypto.randomUUID(),
        title: titleInput.value.trim(),
        description: document.querySelector('#task-description').value.trim(),
        category: document.querySelector('#task-category').value,
        status: document.querySelector('#task-status').value,
        dueDate: dateInput.value,
    };

    if (taskId) {
        const index = tasks.findIndex((item) => item.id === taskId);
        if (index !== -1) {
            tasks[index] = task;
        }
    } else {
        tasks.unshift(task);
    }

    saveTasks(tasks);
    closeTaskModal();
    renderTasks();
});

taskTableBody?.addEventListener('click', (event) => {
    const button = event.target.closest('[data-action]');
    if (!button) {
        return;
    }

    const tasks = readTasks();
    const task = tasks.find((item) => item.id === button.dataset.taskId);

    if (button.dataset.action === 'edit' && task) {
        openTaskModal(task);
    }

    if (button.dataset.action === 'delete' && task) {
        if (window.confirm(`Delete "${task.title}"?`)) {
            saveTasks(tasks.filter((item) => item.id !== task.id));
            renderTasks();
        }
    }
});

document.querySelector('#task-search')?.addEventListener('input', filterTasks);
document.querySelector('#category-filter')?.addEventListener('change', filterTasks);
document.querySelector('#status-filter')?.addEventListener('change', filterTasks);

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && taskModal && !taskModal.hidden) {
        closeTaskModal();
    }
});

renderTasks();