import { TableBody } from '../TableBody'
import { TableColGroup } from '../TableColGroup'
import { TableFooterAgg } from '../TableFooterAgg'
import { TableHeader } from '../TableHeader'

export const PrintableMode = (params) => {
    // console.log(params)
    const { settings, columns } = params
    const columns1 = columns.filter((item) => item.renderer !== 'action')
    params = { ...params, columns: columns1 }

    const { id, tableWidth } = params.tableParams
    const { table_css, } = settings.cssClass

    let tableHeader = TableHeader(params)
    let tableBody = TableBody(params)
    let tableFooterAgg = TableFooterAgg(params)
    let tableColGroup = TableColGroup(params)

    let table = `<div 
        class="table-wrp block overflow-x-auto"
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
