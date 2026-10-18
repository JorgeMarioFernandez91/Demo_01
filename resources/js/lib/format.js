const relative = new Intl.RelativeTimeFormat(undefined, { numeric: 'auto' });

const units = [
    ['year', 60 * 60 * 24 * 365],
    ['month', 60 * 60 * 24 * 30],
    ['week', 60 * 60 * 24 * 7],
    ['day', 60 * 60 * 24],
    ['hour', 60 * 60],
    ['minute', 60],
];

export function timeAgo(date) {
    const seconds = (new Date(date).getTime() - Date.now()) / 1000;

    for (const [unit, size] of units) {
        if (Math.abs(seconds) >= size) {
            return relative.format(Math.round(seconds / size), unit);
        }
    }

    return 'just now';
}

export function formatDate(date) {
    return new Date(date).toLocaleString(undefined, { dateStyle: 'medium', timeStyle: 'short' });
}

export function initials(name) {
    return name
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0].toUpperCase())
        .join('');
}

export function categoryLabel(category) {
    if (!category) {
        return 'Uncategorized';
    }

    return category.replace(/-/g, ' ').replace(/^\w/, (c) => c.toUpperCase());
}
