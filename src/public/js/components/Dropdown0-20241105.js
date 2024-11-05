let k = {},
    listenersOfDropdown2 = [],
    filtersOfDropdown2 = [],
    debugListener = false,
    debugFlow = false
const makeIdForNumber = (n) => '#' + String(n).padStart(6, '0').substring(0, 3) + '.' + String(n).padStart(6, '0').substring(3)
const makeId = (n) => (isNaN(n) ? '' : makeIdForNumber(n))
// const makePrefix = () => isNaN(state.id) ? state.id : makeId(state.id)
const intersect = (arr1, arr2) => {
    const set2 = new Set(arr2)
    return arr1.filter((item) => set2.has(item))
}
const select2FormatSelected = (state) => {
    let s = ''
    s += state.text
    if (state.id) s += `<a class="mx-2 text-blue-600" href="console.log(${state.id})"><i class="fa-duotone fa-share-from-square"></i></a>`
    // if (state.title) s += ' <span class="text-gray-500">(' + state.title + ')</span>'
    return s
}
const select2FormatOption = (state) => {
    return !state.title
        ? $(`<div class="px-1" title="#${state.id}"><span>${state.text}</span></div>`)
        : $(
              `<div class="flex justify-between px-1" title="#${state.id}">
        <span>${state.text}</span>
        <pre>   </pre>
        <span>${state.title}</span>
    </div>`,
          )
}

const select2Matcher = (params, data) => {
    // console.log(params, data)
    // If there are no search terms, return all of the data.
    if ($.trim(params.term) === '') return data

    // Check if the primary text or the secondary text matches the search term.
    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1 || data.title.toLowerCase().indexOf(params.term.toLowerCase()) > -1)
        return data

    // If it doesn't match, return null.
    return null
}
const getEById = (id) => $("[id='" + id + "']")

const getIsMultipleOfE = (id) => (getEById(id)[0] ? getEById(id)[0].hasAttribute('multiple') : false)
const getControlTypeOfE = (id) => getEById(id).attr('controlType')
const getAllowClear = (id) => !!(getEById(id).attr('allowClear') === 'true')
const getLetUserChooseWhenOneItem = (id) => !!(getEById(id).attr('letUserChooseWhenOneItem') === 'true')
const getColSpanOfE = (id) => getEById(id).attr('colSpan')
const getReadOnlyOfE = (id) => getEById(id).attr('readOnly')
const getAllVariablesFromExpression = (str) => {
    const regex = new RegExp('["|\']?[a-zA-Z_]+[a-zA-Z0-9]*["|\']?', 'gm'),
        result = []
    let m
    while ((m = regex.exec(str)) !== null) {
        // This is necessary to avoid infinite loops with zero-width matches
        if (m.index === regex.lastIndex) regex.lastIndex++
        m.forEach((match) => {
            if (match[0] == '"' && match[match.length - 1] == '"') return //remove "closed" or "new;pending"
            if (match[0] == "'" && match[match.length - 1] == "'") return //remove 'closed' or 'new;pending'
            result.push(match)
        })
    }
    // console.log(result)
    return result
}
const getSecondsFromTime = (hms) => {
    var a = hms.split(':') // split it at the colons
    switch (a.length) {
        case 1:
            return +a[0]
        case 2:
            return +a[0] * 60 * 60 + +a[1] * 60 // HH:MM
        case 3:
            return +a[0] * 60 * 60 + +a[1] * 60 + +a[2]
    }
}
const getDaysFromDateSlash = (dmy) => moment(dmy, 'DD/MM/YYYY').diff(moment('1970-01-01'), 'days')
const getDaysFromDateDash = (dmy) => moment(dmy, 'YYYY-MM-DD').diff(moment('1970-01-01'), 'days')
const getValueOfEById = (id) => {
    const isMultipleOfE = getIsMultipleOfE(id)
    const controlType = getControlTypeOfE(id)
    // console.log(id, isMultipleOfE, controlType)
    if (controlType === 'radio_or_checkbox') {
        const control = isMultipleOfE ? 'checkbox' : 'radio'
        const name = isMultipleOfE ? id + '[]' : id
        const controlName = 'input:' + control + "[name='" + name + "']:checked"
        let value = []
        if (isMultipleOfE) {
            const checkboxes = $(controlName)
            for (let i = 0; i < checkboxes.length; i++) {
                value.push(checkboxes[i].value)
                // console.log(checkboxes[i].value)
            }
        } else {
            value.push($(controlName).val())
        }
        // console.log(controlName, value)
        return value
    }
    return getEById(id).val()
}
const setValueOfEById = (id, value) => {
    const debugSetValue = false
    const isMultipleOfE = getIsMultipleOfE(id)
    const controlType = getControlTypeOfE(id)
    // console.log(id, isMultipleOfE, controlType)
    let queryStr = ''
    if (controlType === 'radio_or_checkbox') {
        // console.log(id, value)
        if (isMultipleOfE) {
            // $("input:checkbox[name='dropdownMonitors()[]']").prop("checked",false)
            queryStr = "input:checkbox[name='" + id + "[]']"
            $(queryStr).prop('checked', false)
            if (debugSetValue) console.log('Unchecked all', queryStr)
            value.forEach((id_id) => {
                queryStr = "input:checkbox[name='" + id + "[]'][value=" + id_id + ']'
                $(queryStr).prop('checked', true)
                if (debugSetValue) console.log('Checking', queryStr)
            })
        } else {
            // $("input:radio[name='assignee_1'][value=765]").prop("checked",true)
            if (value == null) {
                queryStr = "input:radio[name='" + id + "']"
                $(queryStr).prop('checked', false)
            } else {
                queryStr = "input:radio[name='" + id + "'][value=" + value + ']'
                $(queryStr).prop('checked', true)
            }
            if (debugSetValue) console.log(queryStr)
        }
    } else {
        //Dropdown and dropdownMulti
        getEById(id).val(value)
    }
}
// const removeParenthesis = (str) => (str.includes("()")) ? str.substring(0, str.length - 2) : str

const convertStrToNumber = (varValue) => {
    const debugListener = false
    let type = 'normal_number'
    if (varValue.includes(',')) type = 'number_with_commas'
    else if (isNaN(varValue)) type = 'datetime_string'

    // console.log(varValue, isNaN(varValue), type)
    if (varValue) {
        switch (type) {
            case 'normal_number':
                break
            case 'number_with_commas':
                varValue = (varValue + '').replace(/\,/g, '') * 1 //<<Remove all "," as the thousand separator
                break
            case 'datetime_string':
                const includedHour = varValue.includes(':')
                const includedDateSlash = varValue.includes('/')
                const includedDateDash = varValue.includes('-')
                if (includedHour && includedDateSlash) {
                    /* 01/01/2023 12:34 */
                    const datetime = varValue.split(' ')
                    const date = datetime[0]
                    const time = datetime[1]
                    varValue = getDaysFromDateSlash(date) * 24 * 3600 + getSecondsFromTime(time)
                } else if (includedHour && includedDateDash) {
                    /* 2023-01-01 12:34 */
                    const datetime = varValue.split(' ')
                    const date = datetime[0]
                    const time = datetime[1]
                    varValue = getDaysFromDateDash(date) * 24 * 3600 + getSecondsFromTime(time)
                } else {
                    if (includedHour) {
                        /* 12:34 */
                        varValue = getSecondsFromTime(varValue)
                    } else if (includedDateSlash) {
                        /* 01/02/2023 */
                        varValue = getDaysFromDateSlash(varValue) * 24 * 3600
                    } else if (includedDateDash) {
                        /* 2023-01-01 */
                        varValue = getDaysFromDateDash(varValue) * 24 * 3600
                    }
                }
                break
            default:
                console.log('Unknown how to convert string to value: ' + varValue)
        }

        if (debugListener) console.log(varName, varValue)
        return varValue
    }
}
