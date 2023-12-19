import { TableColGroup } from '../TableColGroup'
import { TableFooter } from '../TableFooter'
import { TableHeader } from '../TableHeader'
import { TableRows } from '../TableRows'

export const PrintMode = (params) => {
    // console.log(params)
    const { columns, dataSource, settings } = params
    const { id, maxH, showPaginationTop, tableWidth } = params.tableParams
    const { table_css, } = settings.cssClass

    let tableHeader = TableHeader({ columns, settings })
    let tableRows = TableRows({ columns, settings, dataSource })
    let tableFooter = TableFooter({ columns, settings })
    let tableColGroup = TableColGroup({ columns, settings })

    let table = `<div 
        class="table-wrp block overflow-x-auto ${maxH} ${showPaginationTop ? "border-t" : "rounded-t-lg"}"
        style="table-layout: auto; ${tableWidth}"
        >
        <table id="${id}" class="${table_css}">
            ${tableColGroup}
            ${tableHeader}
            ${tableRows}
            ${tableFooter}
        </table>
    </div>`

    return table
}
