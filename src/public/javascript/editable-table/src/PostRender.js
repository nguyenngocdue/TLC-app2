import { applyFixedColumnWidth } from './FrozenColumn'

export const postRender = (params) => {
    $(document).ready(() => {
        const { id, } = params.tableParams
        const { columns } = params
        console.log("Loaded " + id)
        console.log(id, columns)
        applyFixedColumnWidth(id, columns)
    })
}