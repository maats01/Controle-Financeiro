function handlePerPageChange(selectElement) {
    const selectedValue = selectElement.value;
    const currentUrl = new URL(window.location.href);

    currentUrl.searchParams.set('per_page', selectedValue);

    let paramsToDelete = [];
    for (let key of currentUrl.searchParams.keys()) {
        if (key.startsWith('page')) {
            paramsToDelete.push(key);
        }
    }

    if (currentUrl.searchParams.has('page')) {
        currentUrl.searchParams.set('page', '1'); // or delete it to default to 1
    }

    window.location.href = currentUrl.toString();
}