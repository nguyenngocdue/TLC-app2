import { focusToControl, getControl, setCurrentValue, } from './Controls/Controls'
import { getCurrentValue, getInputElement, postRenderControl, } from './Controls/Controls'
import { attachControlEventHandler } from './Controls/ControlEventHandlers'
import { getEById } from './functions'

export const AddEventListenerForDirectControls = (params, tableId) => {
    const { columns } = params
    const dataSource = editableTableValues[tableId]
    // console.log(params, tableId, dataSource)

    const controls = Object.keys(columns).filter(dataIndex => (columns[dataIndex].control && !columns[dataIndex].renderer))
    // console.log(controls)

    controls.map(dataIndex => {
        const column = columns[dataIndex]
        const { control } = column
        // console.log(column)

        switch (control) {
            case 'toggle':
                Object.keys(dataSource).map((dataSourceIndex) => {
                    const controlId = `${tableId}_${dataIndex}_${dataSourceIndex}`
                    getEById(controlId).change((e) => {
                        const newValue = e.target.checked ? 1 : 0
                        console.log("Onchange toggle", controlId, newValue)
                        setCurrentValue(tableId, dataIndex, dataSourceIndex, newValue)
                    })
                })
                return
            default:
                return
        }
    })
}