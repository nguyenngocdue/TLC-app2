import { TableBody } from '../TableBody'
import { TableColGroup } from '../TableColGroup'
import { TableFooterAgg } from '../TableFooterAgg'
import { TableHeader } from '../TableHeader'

export const PrintableMode = (params) => {
    // console.log(params)
    const { columns } = params
    const columns1 = columns.filter((item) => item.renderer !== 'action')
    params = { ...params, columns: columns1 }

    const { tableId, tableWidth } = params.tableParams

    let tableHeader = TableHeader(params)
    let tableBody = TableBody(params)
    let tableFooterAgg = TableFooterAgg(params)
    let tableColGroup = TableColGroup(params)

    let table = `<div 
        class="table-wrp block overflow-x-auto"
        style="table-layout: auto; ${tableWidth}"
        >
        <table id="${tableId}" class="w-full border-separate border-spacing-0 min-w-full max-w-full">
            ${tableColGroup}
            ${tableHeader}
            ${tableBody}
            ${tableFooterAgg}
        </table>
    </div>`

    return table
}
