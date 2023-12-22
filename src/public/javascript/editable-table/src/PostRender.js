import { applyFixedColumnWidth } from './FrozenColumn'
import { keyBy } from './EditableTable'
import { AddEventListenerForEditableControls } from './AttachDirectControls'
import { AddEventListenerForDirectControls } from './AttachEditableControls'

const applyFixedColumns = (params, tableId) => {
    const { columns } = params
    applyFixedColumnWidth(tableId, columns)
}

const exposeParamsToWindow = (params, tableId) => {
    if (!window.editableTables) window.editableTables = {}
    window.editableTables[tableId] = params

    if (!window.editableTableValues) window.editableTableValues = {}
    // console.log(keyBy(params.dataSource, 'id'))
    window.editableTableValues[tableId] = keyBy(params.dataSource, 'id')
}

const listenOnResizeWindow = (params, tableId) => {
    window.addEventListener('resize', () => {
        // const width = window.innerWidth;
        // const height = window.innerHeight;
        applyFixedColumns(params, tableId)
    })
}

export const preRender = (params) => {
    const { tableId } = params.tableParams
    exposeParamsToWindow(params, tableId)
}

export const postRender = (params) => {
    $(document).ready(() => {
        const { tableId } = params.tableParams
        applyFixedColumns(params, tableId)
        AddEventListenerForEditableControls(params, tableId)
        AddEventListenerForDirectControls(params, tableId)
        // listenOnResizeWindow(params, tableId)
    })
}