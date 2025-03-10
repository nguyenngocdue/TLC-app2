const toggleCheckboxCache = {}
const toggleCheckbox = (yAxisId, route = null) => {
    const className = "view-all-matrix-checkbox-" + yAxisId
    if (toggleCheckboxCache[className]) {
        toggleCheckboxCache[className] = !toggleCheckboxCache[className]
    } else {
        toggleCheckboxCache[className] = true
    }

    const value = toggleCheckboxCache[className]
    const allCheckbox = $("." + className)
    allCheckbox.prop('checked', value);

    if (route) {
        for (let i = 0; i < allCheckbox.length; i++) {
            const checkbox = allCheckbox[i]
            const status = $("#" + checkbox.id).attr("status")
            determineNextStatuses(checkbox.name, status, value, route)
        }
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

const sendManyRequestCache = {}
const sendManyRequest = (uid, sheetTable, sheetId, matrixKey) => {
    // console.log(uid, sheetTable, sheetId, matrixKey)
    const key0 = matrixKey
    const key1 = `${uid}|${sheetTable}|${sheetId}`
    const divCheckIdIcon = `divCheck_${uid}_${sheetTable}_${sheetId}`
    if(sendManyRequestCache[key0] === undefined ) {
        sendManyRequestCache[key0] = {}
    }
    if(sendManyRequestCache[key0][key1] === undefined) {
        sendManyRequestCache[key0][key1] = true
        $(`#${divCheckIdIcon}`).show()
    }
    else {
        delete sendManyRequestCache[key0][key1]
        $(`#${divCheckIdIcon}`).hide()
    }
    // console.log(sendManyRequestCache[key0])
    const total = Object.keys(sendManyRequestCache[key0]).length
    
    let button = ''
    
        const text = total ? `Send ${total} Sign-Off Request${total>1?'s':''}` : `Send Sign-off Request`
        button = document.createElement('button')
        button.classList.add(`rounded`, `text-white`, `p-2`, `font-semibold`)
        button.classList.add(total?`bg-blue-600`:`bg-blue-300`, total?`cursor-pointer`:`cursor-not-allowed`)
        button.innerHTML=text
        button.type='button'
        button.addEventListener('click', (e)=>{
            const allKeys = Object.keys(sendManyRequestCache[key0])
            const btn = e.target
            const msg = "Sending " + allKeys.length +" request(s) to sign-off..."
            console.log(msg)
            toastr.info(msg)
            btn.innerHTML = "Requesting..."
            btn.classList.add('bg-blue-300', `cursor-not-allowed`)
            btn.classList.remove('bg-blue-600', `cursor-pointer`)
            btn.disabled = true
            const splitted = allKeys.map(key=>key.split("|"))
            // console.log("Sending ", sendManyRequestCache, splitted  )
            const results = {}
            splitted.forEach(term=>{
                const docUnique = term[1]+'|'+term[2]
                if(results[docUnique] === undefined)  results[docUnique] = []
                results[docUnique].push(+term[0])
            })
            // console.log("Results", results)

            Object.keys(results).forEach(docUnique=>{
                let category = ''
                const [tableName, signableId] = docUnique.split("|")
                switch(tableName) {
                    case 'qaqc_insp_chklst_shts':
                        category = "signature_qaqc_chklst_3rd_party"
                        break;
                    case 'qaqc_punchlists':
                        category = "signature_qaqc_punchlist_qaqc"
                        break;
                }
                const data = { 
                    tableName,
                    signableId,
                    uids: results[docUnique],
                    category,
                    wsClientId,
                }
                const url = `/api/v1/qaqc/request_to_sign_off`
                // console.log(url, data)
                $.ajax({
                    method:'POST',
                    url,
                    data,
                }).then(res=>{
                    setTimeout(()=>{
                        window.location.reload()
                    }, 2000) 
                    /* Wait for the toastr to show */
                    /* 1000ms is not long enough to write into DB*/
                })
            })
        })
    // console.log("Attach button to divSendManyRequest"+matrixKey)
    $("#divSendManyRequest"+matrixKey).html(button)
}


const determineNextStatusesCache = {}
const determineNextStatuses = (id, status, value, route) => {
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
    const ids = Object.keys(determineNextStatusesCache)

    const { statuses } = superProps
    const result0 = []
    for (let i = 0; i < uniqueValuesArray.length; i++) {
        const status = uniqueValuesArray[i]
        const { transitions } = statuses[status]
        result0.push(transitions)
        // console.log(status, transitions, statuses[status])
    }
    const result = intersectionArraysOfArrays(result0)
    // console.log(uniqueValuesArray, statuses, result);

    const buttons = []
    for (let i = 0; i < result.length; i++) {
        const status = result[i]
        const statusObj = statuses[status]
        const actionButton = statusObj['action-buttons']
        const { label, } = actionButton
        const { change_status_multiple = false } = actionButton
        // console.log(statusObj)
        const parsedDocument = (new DOMParser()).parseFromString(statusObj.icon, 'text/html');
        const icon = parsedDocument.body.firstChild;
        // console.log(statusObj.icon, parsedDocument, icon)

        const caption = document.createElement('span')
        caption.innerHTML = " " + label

        const button = document.createElement('button');
        if (statusObj.icon) button.appendChild(icon);
        button.appendChild(caption);
        button.type = 'button'
        if (!change_status_multiple) {
            // button.disabled = true
            continue;
        } else {
            button.addEventListener('click', function () {
                changeStatusAll(route, ids, status, label)
                console.log('Button clicked!', route, status, label, ids)
            });
        }

        let classesToAdd = 'px-2.5 py-2 font-medium leading-tight rounded transition duration-150 ease-in-out focus:ring-0 focus:outline-n1one disabled:opacity-50 inline-block text-sm border-2 hover:bg-black hover:bg-opacity-5 mr-1' // Add CSS class
        classesToAdd += " text-" + statusObj.text_color
        classesToAdd += " bg-" + statusObj.bg_color
        classesToAdd += " border-" + statusObj.text_color
        button.classList.add(...classesToAdd.split(' '))
        buttons.push(button)
    }
    div.html(buttons)
}