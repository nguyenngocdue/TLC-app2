import { TableColGroup } from '../TableColGroup'
import { TableFooterAgg } from '../TableFooterAgg'
import { TableHeader } from '../TableHeader'
import { TableHeaderToolbar } from '../TableHeaderToolbar'
import { TableRows } from '../TableRows/TableRows'

export const EditableMode = (params) => {
    // console.log(params)
    const { columns, dataSource, settings } = params
    const { id, maxH, showPaginationTop, tableWidth } = params.tableParams
    const { table_css, } = settings.cssClass

    const tableHeader = TableHeader({ columns, settings })
    const tableHeaderToolbar = TableHeaderToolbar({ columns, settings })
    const tableRows = TableRows({ columns, settings, dataSource })
    const tableFooterAgg = TableFooterAgg({ columns, settings })
    const tableColGroup = TableColGroup({ columns, settings })

    const table = `<div 
        class="table-wrp block overflow-x-auto ${maxH} ${showPaginationTop ? "border-t" : "rounded-t-lg"}"
        style="table-layout: auto; ${tableWidth}"
        >
        <table id="${id}" class="${table_css}">
            ${tableColGroup}
            ${tableHeader}
            ${tableHeaderToolbar}
            ${tableRows}
            ${tableFooterAgg}
        </table>
    </div>`

    return table
}
