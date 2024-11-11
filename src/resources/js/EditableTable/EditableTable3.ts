import { TableColumn } from './EditableTable3ColumnType'
import { TableDataLine } from './EditableTable3DataLineType'
import { TableConfig } from './EditableTable3ConfigType'

import { makeTbody } from './EditableTable3TBody'
import { makeThead } from './EditableTable3THead'
import { makeTfoot } from './EditableTable3TFoot'
import { makeToolBarTop } from './EditableTable3ToolbarTop'
import { makeToolBarBottom } from './EditableTable3ToolbarBottom'

class EditableTable3 {
    private defaultConfig: TableConfig = {
        maxH: null,
        borderColor: 'border-gray-300',
    }

    constructor(
        private params: {
            tableName: string
            tableConfig: TableConfig
            columns: TableColumn[]
            dataSource: TableDataLine[]
        },
    ) {
        console.log('EditableTable3', params)
    }

    render() {
        const { tableName, columns, dataSource, tableConfig = {} } = this.params

        const maxH = tableConfig.maxH || this.defaultConfig.maxH
        const borderColor = tableConfig.borderColor || this.defaultConfig.borderColor
        const borderT = tableConfig.showPaginationTop ? `border-t ${borderColor}` : 'rounded-t-lg'
        const tableWidth = tableConfig.width || 'width: 100%;'

        const toolbarTop = tableConfig.showPaginationTop ? makeToolBarTop(this.params) : ``
        const toolbarBottom = tableConfig.showPaginationBottom ? makeToolBarBottom(this.params) : ``

        const tableHeader = tableConfig.tableHeader || ''
        const tableFooter = tableConfig.tableFooter || ''

        const tableStr = `<table 
            class="whitespace-no-wrap w-full text-sm text-sm-vw border-separate border border-spacing-0 ${borderColor}"
            style="table-layout: auto; ${tableWidth}"
            >
            <colgroup>
            
            </colgroup>
            <thead class="sticky z-10 top-0">
                ${makeThead(this.params)}
            </thead>
           
            <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                ${makeTbody(this.params)}
            </tbody>
            <tfoot>
                ${makeTfoot(this.params)}
            </tfoot>
        </table>`

        const wrappingDiv = `<div class="table-wrp block bg-gray-100 overflow-x-auto ${maxH} ${borderT}">
            ${tableStr}
        </div>`

        const editableTable = `${tableHeader}${toolbarTop}${wrappingDiv}${toolbarBottom}${tableFooter}`

        const divId = `#${tableName}`
        const div = document.querySelector(divId)
        div && (div.innerHTML = editableTable)
    }
}

// Expose EditableTable3 to the global window object
;(window as any).EditableTable3 = EditableTable3
