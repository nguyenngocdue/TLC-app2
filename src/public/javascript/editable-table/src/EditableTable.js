import { TableColGroup } from './TableColGroup'
import { TableFooter } from './TableFooter'
import { TableHeader } from './TableHeader'
import { TableRows } from './TableRows'

const tableSettings = {
    id: "tableId",
    maxH: 1000,
    showPaginationTop: false,
    tableWidth: 100,
}

export const EditableTable = (params) => {
    // console.log(params)
    const { columns, dataSource } = params
    const { id, maxH, showPaginationTop, tableWidth } = tableSettings

    let tableHeader = TableHeader({ columns })
    let tableRows = TableRows({ columns, dataSource })
    let tableFooter = TableFooter({ columns })
    let tableColGroup = TableColGroup({ columns })

    let table = `<div 
        class="table-wrp block overflow-x-auto ${maxH} ${showPaginationTop ? "border-t" : "rounded-t-lg"}"
        style="table-layout: auto; ${tableWidth}"
        >
        <table id="${id}" class="whitespace-no-wrap w-full text-sm border-separate border-spacing-0">
            ${tableColGroup}
            ${tableHeader}
            ${tableRows}
            ${tableFooter}
        </table>
    </div>`

    return table
}
