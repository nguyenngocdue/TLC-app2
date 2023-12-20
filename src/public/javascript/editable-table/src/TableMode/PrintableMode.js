import { TableBody } from '../TableBody'
import { TableColGroup } from '../TableColGroup'
import { TableFooterAgg } from '../TableFooterAgg'
import { TableHeader } from '../TableHeader'

export const PrintableMode = (params) => {
    // console.log(params)
    const { columns, dataSource, settings } = params
    const { id, maxH, showPaginationTop, tableWidth } = params.tableParams
    const { table_css, } = settings.cssClass

    let tableHeader = TableHeader({ columns, settings })
    let tableBody = TableBody({ columns, settings, dataSource })
    let tableFooterAgg = TableFooterAgg({ columns, settings })
    let tableColGroup = TableColGroup({ columns, settings })

    let table = `<div 
        class="table-wrp block overflow-x-auto ${maxH} ${showPaginationTop ? "border-t" : "rounded-t-lg"}"
        style="table-layout: auto; ${tableWidth}"
        >
        <table id="${id}" class="${table_css}">
            ${tableColGroup}
            ${tableHeader}
            ${tableBody}
            ${tableFooterAgg}
        </table>
    </div>`

    return table
}
