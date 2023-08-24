const toggleCheckboxCache = {}
const toggleCheckbox = (yAxisId) => {
    const className = "view-all-matrix-checkbox-" + yAxisId
    if (toggleCheckboxCache[className]) {
        toggleCheckboxCache[className] = !toggleCheckboxCache[className]
    } else {
        toggleCheckboxCache[className] = true
    }

    const value = toggleCheckboxCache[className]
    const allCheckbox = $("." + className)
    allCheckbox.prop('checked', value);

    for (let i = 0; i < allCheckbox.length; i++) {
        const checkbox = allCheckbox[i]
        const status = $("#" + checkbox.id).attr("status")
        determineNextStatuses(checkbox.name, status, value)
    }
}

const getUniqueValueFromObject = (object) => {
    const values = Object.values(object); // Extract values from the object
    return getUniqueValueFromArray(values)
}

const getUniqueValueFromArray = (array) => {
    const uniqueValuesSet = new Set(array); // Create a Set from the array
    const uniqueValuesArray = Array.from(uniqueValuesSet); // Convert the Set back to an array
    return uniqueValuesArray
}

function intersectionArraysOfArrays(arrays) {
    if (arrays.length === 0) return [];

    // Convert the first subarray to a Set
    const intersectionSet = new Set(arrays[0]);

    // Iterate through the remaining subarrays
    for (let i = 1; i < arrays.length; i++) {
        // Create a Set for the current subarray
        const currentSet = new Set(arrays[i]);

        // Filter the intersectionSet to keep only elements present in the current subarray
        intersectionSet.forEach(value => { if (!currentSet.has(value)) intersectionSet.delete(value); });
    }

    // Convert the intersectionSet back to an array
    const result = Array.from(intersectionSet);
    return result;
}

const determineNextStatusesCache = {}
const determineNextStatuses = (id, status, value) => {
    const div = $("#divApproveMulti")
    // console.log("determineNextStatuses", id, status, value, div.length);
    if (div.length === 0) return;

    if (value) {
        determineNextStatusesCache[id] = status
    } else {
        delete (determineNextStatusesCache[id])
    }
    // console.log(determineNextStatusesCache)

    const uniqueValuesArray = getUniqueValueFromObject(determineNextStatusesCache)

    const { statuses } = superProps
    const result0 = []
    for (let i = 0; i < uniqueValuesArray.length; i++) {
        const status = uniqueValuesArray[i]
        const { transitions } = statuses[status]
        result0.push(transitions)
        // console.log(status, transitions, statuses[status], result)
    }
    const result = intersectionArraysOfArrays(result0)
    // console.log(uniqueValuesArray, statuses, result);

    const buttons = []
    for (let i = 0; i < result.length; i++) {
        const status = result[i]
        const statusObj = statuses[status]
        // console.log(statusObj)
        const parsedDocument = (new DOMParser()).parseFromString(statusObj.icon, 'text/html');
        const icon = parsedDocument.body.firstChild;
        // console.log(statusObj.icon, parsedDocument, icon)

        const caption = document.createElement('span')
        caption.innerHTML = " " + statusObj['action-buttons']['label']

        const button = document.createElement('button');
        if (statusObj.icon) button.appendChild(icon);
        button.appendChild(caption);
        button.type = 'button'

        let classesToAdd = 'px-2.5 py-2 font-medium leading-tight rounded transition duration-150 ease-in-out focus:ring-0 focus:outline-n1one disabled:opacity-50 inline-block text-sm border-2 hover:bg-black hover:bg-opacity-5 mr-1' // Add CSS class
        classesToAdd += " text-" + statusObj.text_color
        classesToAdd += " bg-" + statusObj.bg_color
        classesToAdd += " border-" + statusObj.text_color
        button.classList.add(...classesToAdd.split(' '))
        buttons.push(button)
    }
    div.html(buttons)
}