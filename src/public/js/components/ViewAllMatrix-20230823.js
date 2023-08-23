const toggleCheckboxCache = {}
const toggleCheckbox = (yAxisId) => {
    const className = "view-all-matrix-checkbox-" + yAxisId
    if (toggleCheckboxCache[className]) {
        toggleCheckboxCache[className] = !toggleCheckboxCache[className]
    } else {
        toggleCheckboxCache[className] = true
    }

    $("." + className).prop('checked', toggleCheckboxCache[className]);
}