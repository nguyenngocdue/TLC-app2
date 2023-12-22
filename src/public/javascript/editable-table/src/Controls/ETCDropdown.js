import { looseInclude } from '../functions'

export const ETCDropdown = (controlParams) => {
    const { currentValue, column, controlId } = controlParams
    const { dataSource, multiple = false } = column
    // console.log(column, dataSource)
    const options = Object.keys(dataSource).map((id) => {
        const option = dataSource[id]['name']
        // console.log(option, id, cell)
        let selected
        if (multiple) {
            selected = looseInclude(currentValue, id) ? "selected" : ""
        } else {
            selected = (id == currentValue) ? "selected" : ""
        }
        // const selected = (id == currentValue) ? "selected" : ""
        return `<option value="${id}" ${selected}>${option}</option>`
    })
    // console.log(options)
    const multipleStr = multiple ? "multiple" : ""
    return `<select id="${controlId}" class="w-full" ${multipleStr}>
        ${options.join('')}
    </select>`
    // return `<input type="text" class="w-full" value="${cell}" >`
}