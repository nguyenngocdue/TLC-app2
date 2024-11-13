import { TableConfig } from './Type/EditableTable3ConfigType'
import { TableParams } from './Type/EditableTable3ParamType'

import { makeTbody } from './EditableTable3TBody'
import { makeThead } from './EditableTable3THead'
import { makeTfoot } from './EditableTable3TFoot'
import { makeToolBarTop } from './EditableTable3ToolbarTop'
import { makeToolBarBottom } from './EditableTable3ToolbarBottom'
import {
    ColumnNoValue,
    convertArrayToLengthAware,
    makeUpDefaultValue,
} from './EditableTable3DefaultValue'
import { calTableTrueWidth, makeColGroup } from './EditableTable3ColGroup'
import { makeThead2nd } from './EditableTable3THead2nd'
import { applyFixedColumnWidth, applyTopFor2ndHeader } from './EditableTable3FixedColumn'
import { em } from '@fullcalendar/core/internal-common'

class EditableTable3 {
    private defaultConfig: TableConfig = {
        borderColor: 'border-gray-300',
    }

    constructor(private params: TableParams) {
        this.params.columns = makeUpDefaultValue(params)
        if (!this.params.tableConfig) this.params.tableConfig = {}
        if (Array.isArray(params.dataSource))
            this.params.dataSource = convertArrayToLengthAware(params.dataSource)
        if (this.params.tableConfig.showNo) this.params.columns.unshift(ColumnNoValue)
        // makeUpPaginator(this.params.tableConfig, this.params.dataSource)

        console.log('EditableTable3', { ...params, columns: this.params.columns })
    }

    renderTable() {
        const { tableName, tableConfig, columns, dataSource } = this.params

        if (!columns) {
            const divId = `#${tableName}`
            const div = document.querySelector(divId)
            const editableTable = `<div class=" text-center rounded m-1 p-2 bg-yellow-400 text-red-500">
                Columns is required
            </div>`
            div && (div.innerHTML = editableTable)
        }

        const tableDebug = tableConfig.tableDebug || false
        const borderColor = tableConfig.borderColor || this.defaultConfig.borderColor
        const borderT = tableConfig.showPaginationTop ? `border-t ${borderColor}` : 'rounded-t-lg'

        let tableWidth = 'width: 100%;'
        if (tableConfig.tableTrueWidth) tableWidth = `width: ${calTableTrueWidth(this.params)}px;`

        //OLD maxH using REM and tailwind generator, up to 60 REM. 1 REM = 16px
        //Can be removed if all code use px not rem
        const styleMaxH = tableConfig.maxH
            ? `max-height: ${tableConfig.maxH <= 60 ? tableConfig.maxH * 16 : tableConfig.maxH}px;`
            : ''

        const toolbarTop = tableConfig.showPaginationTop ? makeToolBarTop(this.params) : ``
        const toolbarBottom = tableConfig.showPaginationBottom ? makeToolBarBottom(this.params) : ``

        const tableHeader = tableConfig.tableHeader
            ? `<div component="tableHeader">${tableConfig.tableHeader}</div>`
            : ''
        const tableFooter = tableConfig.tableFooter
            ? `<div component="tableFooter">${tableConfig.tableFooter}</div>`
            : ''

        const body = makeTbody(this.params)
        const emptyTable = `<tr><td class='text-center h-40 text-gray-500' colspan='100%'>No Data</td></tr>`

        const tableStr = `<table 
                class="whitespace-no-wrap w-full text-sm text-sm-vw border-separate 1border border-spacing-0 ${borderColor}"
                style="table-layout: auto; ${tableWidth}"
            >
            <colgroup>
                ${makeColGroup(this.params)}
            </colgroup>
            <thead class="sticky z-10 bg-gray-100" style="top:0px;">
                ${makeThead(this.params)}
            </thead>
            
            <thead class="sticky z-10 bg-gray-100 second-header">
                ${makeThead2nd(this.params)}
            </thead>
           
            <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                ${body ? body : emptyTable}
            </tbody>

            <tfoot>
                ${makeTfoot(this.params)}
            </tfoot>
        </table>`

        const classList = `table-wrp block bg-gray-100 overflow-x-auto ${borderT} border-l border-r border-b`
        const styleList = `${styleMaxH}`

        const wrappingDiv = `<div class="${classList}" style="${styleList}">
            ${tableStr}
        </div>`

        const debugStr = tableDebug
            ? `<div class="bg-red-600 text-white border font-bold">${tableName} is in DEBUG Mode</div>`
            : ``

        const editableTable = `
        ${debugStr}
        ${tableHeader}
        ${toolbarTop}
        ${wrappingDiv}
        ${toolbarBottom}
        ${tableFooter}
        `

        return editableTable
    }

    render() {
        const { tableName, tableConfig, columns, dataSource } = this.params

        let body = ''
        if (!columns) {
            body = `<div class=" text-center rounded m-1 p-2 bg-yellow-400 text-red-500">
            Columns is required
            </div>`
        }

        if (columns && !dataSource) {
            body = `<div class=" text-center rounded m-1 p-2 bg-yellow-400 text-red-500">
            DataSource is required
            </div>`
        }

        if (columns && dataSource) {
            body = this.renderTable()
        }

        const divId = `#${tableName}`
        const div = document.querySelector(divId)
        div && (div.innerHTML = body)

        if (columns && dataSource) {
            setTimeout(() => {
                //Wait sometime for the browser to finish rendering the table
                applyFixedColumnWidth(tableName, this.params.columns)
                applyTopFor2ndHeader(tableName)
            }, 1000)
        }
    }
}

// Expose EditableTable3 to the global window object
;(window as any).EditableTable3 = EditableTable3
