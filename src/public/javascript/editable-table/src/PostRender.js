import { applyFixedColumnWidth } from './FrozenColumn'

const applyFixedColumns = (params) => {
    const { tableId, } = params.tableParams
    const { columns } = params
    applyFixedColumnWidth(tableId, columns)
}

const exposeParamsToWindow = (params) => {
    const { tableId } = params.tableParams
    if (!window.editableTables) window.editableTables = {}
    window.editableTables[tableId] = params
}

export const postRender = (params) => {
    $(document).ready(() => {
        applyFixedColumns(params)
        exposeParamsToWindow(params)
    })
}