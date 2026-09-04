<x-select
    :id="$idPrefix.'status'"
    name="status"
    label="Status"
    :options="['pending' => 'Pending', 'completed' => 'Completed']"
    :selected="request('status', '')"
    placeholder="Any status"
/>

<x-select
    :id="$idPrefix.'priority'"
    name="priority"
    label="Priority"
    :options="['high' => 'High', 'medium' => 'Medium', 'low' => 'Low']"
    :selected="request('priority', '')"
    placeholder="Any priority"
/>

<x-select
    :id="$idPrefix.'category'"
    name="category_id"
    label="Category"
    :options="$categories->pluck('name', 'id')->toArray()"
    :selected="request('category_id', '')"
    placeholder="Any category"
/>

<x-select
    :id="$idPrefix.'due-date'"
    name="due_date"
    label="Due date"
    :options="[
        'today' => 'Today',
        'upcoming' => 'Upcoming',
        'overdue' => 'Overdue',
        'no_due' => 'No due date',
    ]"
    :selected="request('due_date', '')"
    placeholder="Any due date"
/>

<x-select
    :id="$idPrefix.'sort'"
    name="sort"
    label="Sort"
    :options="[
        'newest' => 'Newest first',
        'oldest' => 'Oldest first',
        'due_soon' => 'Due date',
        'priority_high' => 'High priority first',
    ]"
    :selected="request('sort', 'newest')"
    placeholder="Sort tasks"
/>
