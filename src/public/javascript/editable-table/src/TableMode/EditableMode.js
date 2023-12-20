import { DataSource } from '../DataSource'
import { TableColGroup } from '../TableColGroup'
import { TableFooter } from '../TableFooter'
import { TableHeader } from '../TableHeader'
import { TableHeaderToolbar } from '../TableHeaderToolbar'
import { TableRows } from '../TableRows'

export const EditableMode = (params) => {
    // console.log(params)
    const { columns, dataSource, settings } = params
    const { id, maxH, showPaginationTop, tableWidth } = params.tableParams
    const { table_css, } = settings.cssClass

    const tableHeader = TableHeader({ columns, settings })
    const tableHeaderSub = TableHeaderToolbar({ columns, settings })
    const tableRows = TableRows({ columns, settings, dataSource })
    const tableFooter = TableFooter({ columns, settings })
    const tableColGroup = TableColGroup({ columns, settings })

    const table = `<div 
        class="table-wrp block overflow-x-auto ${maxH} ${showPaginationTop ? "border-t" : "rounded-t-lg"}"
        style="table-layout: auto; ${tableWidth}"
        >
        <table id="${id}" class="${table_css}">
            ${tableColGroup}
            ${tableHeader}
            ${tableHeaderSub}
            ${tableRows}
            ${tableFooter}
        </table>
    </div>`

    return table
}
