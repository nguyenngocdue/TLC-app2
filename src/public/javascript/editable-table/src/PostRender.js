import { applyFixedColumnWidth } from './FrozenColumn'

const applyFixedColumns = (params) => {
    console.log(params)
    const { tableId, } = params.tableParams
    const { columns } = params
    // console.log("Loaded " + id)
    // console.log(id, columns)
    applyFixedColumnWidth(tableId, columns)
}

export const postRender = (params) => {
    $(document).ready(() => {
        setTimeout(() => {
            applyFixedColumns(params)
        }, 1000);
    })
}