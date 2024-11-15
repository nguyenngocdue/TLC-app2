import { TableConfig } from './Type/EditableTable3ConfigType'
import { TableParams } from './Type/EditableTable3ParamType'

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
import { TbodyTrs } from './EditableTable3TBodyTRows'
import { updateVisibleRows } from './EditableTable3VirtualScroll'
import { applyRenderedTbody } from './EditableTable3ApplyRenderedTbody'
import { applyFixedColumnWidth, applyTopFor2ndHeader } from './EditableTable3FixedColumn'

class EditableTable3 {
    private tableDebug = false
    private startTime = new Date().getTime()

    private defaultConfig: TableConfig = {
        borderColor: 'border-gray-300',
    }

    constructor(private params: TableParams) {
        this.tableDebug = this.params.tableConfig.tableDebug || false
        if (this.tableDebug)
            console.log(`┌──────────────────${params.tableName}──────────────────┐`)
        this.params.columns = makeUpDefaultValue(params)
        if (!this.params.tableConfig) this.params.tableConfig = {}
        if (Array.isArray(params.dataSource)) {
            this.params.dataSource = convertArrayToLengthAware(params.dataSource)
            if (this.tableDebug) console.log('convertArrayToLengthAware', this.params.dataSource)
        }
        if (this.params.tableConfig.showNo) this.params.columns.unshift(ColumnNoValue)
        // makeUpPaginator(this.params.tableConfig, this.params.dataSource)

        if (this.tableDebug)
            console.log('EditableTable3', { ...params, columns: this.params.columns })
    }

    renderTable() {
        const { tableName, tableConfig, columns } = this.params

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

        if (this.tableDebug) console.log('Start to make Tbody')
        const trs = new TbodyTrs(this.params).render()
        const emptyTable = `<tr><td class='text-center h-40 text-gray-500 border' colspan='100%'>No Data</td></tr>`

        // if (this.tableDebug) console.log('Start to make Colgroup')
        const colgroupStr = makeColGroup(this.params)
        // if (this.tableDebug) console.log('Start to make Thead')
        const tHeadStr = makeThead(this.params)
        // if (this.tableDebug) console.log('Start to make Thead2nd')
        const tHead2ndStr = makeThead2nd(this.params)
        // if (this.tableDebug) console.log('Start to make Tfoot')
        const tFootStr = makeTfoot(this.params)

        const tableStr = `<table 
                class="whitespace-no-wrap w-full text-sm text-sm-vw border-separate 1border border-spacing-0 ${borderColor}"
                style="table-layout: auto; ${tableWidth}"
            >
            <colgroup>
                ${colgroupStr}
            </colgroup>
            <thead class="sticky z-10 bg-gray-100 first-header" style="top:0px;">
                ${tHeadStr}
            </thead>
            
            <thead class="sticky z-10 bg-gray-100 second-header">
                ${tHead2ndStr}
            </thead>
           
            <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                <tr id="spacer-top"></tr>
                <tr id="visible-rows"></tr>
                <tr id="spacer-bottom"></tr>
            </tbody>

            <tfoot>
                ${tFootStr}
            </tfoot>
        </table>`

        const classList = `table-wrp block bg-gray-100 overflow-x-auto ${borderT} border-l border-r border-b`
        const styleList = `${styleMaxH}`

        const wrappingDiv = `<div class="${classList}" style="${styleList}">
            ${tableStr}
        </div>`

        const debugStr = tableDebug
            ? `<div class="bg-red-600 text-white text-center border font-bold">${tableName} is in DEBUG Mode</div>`
            : ``

        const editableTable = `
        ${debugStr}
        ${tableHeader}
        ${toolbarTop}
        ${wrappingDiv}
        ${toolbarBottom}
        ${tableFooter}
        `

        if (this.tableDebug) console.log('madeEmptyEditableTable Body')

        return { editableTable, trs, emptyTable }
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

        let trs: HTMLTableRowElement[] = []
        if (columns && dataSource) {
            const x = this.renderTable()
            if (x.editableTable) {
                body = x.editableTable
                trs = x.trs
            } else {
                body = x.emptyTable
            }
        }

        const divId = `#${tableName}`
        const div = document.querySelector(divId)
        div && (div.innerHTML = body)

        const tbody = document.querySelector(`${divId} tbody`)
        if (tbody) {
            trs.forEach((tr) => tbody.appendChild(tr))
        }

        if (this.tableDebug) {
            console.log(`└──────────────────${this.params.tableName}──────────────────┘`)
            console.log('')
        }

        const endTime00 = new Date().getTime()
        console.log('EditableTable3.render() took', endTime00 - this.startTime, 'ms')

        //when document is ready
        $(() => {
            const virtualTable = document.querySelector(`${divId} table`) as HTMLTableElement
            const tableContainer = virtualTable.parentElement as HTMLElement
            tableContainer.addEventListener('scroll', () =>
                updateVisibleRows(
                    virtualTable,
                    this.params.dataSource,
                    this.params,
                    this.params.tableConfig.virtualScroll,
                ),
            )

            // Initial render
            updateVisibleRows(
                virtualTable,
                this.params.dataSource,
                this.params,
                this.params.tableConfig.virtualScroll,
            )

            // if (columns && dataSource) {
            //     applyRenderedTbody(this.params)
            //     const endTime01 = new Date().getTime()
            //     console.log('EditableTable3.applyRenderedTbody() took', endTime01 - endTime00, 'ms')

            //     setTimeout(() => {
            //         //Wait sometime for the browser to finish rendering the table
            //         applyFixedColumnWidth(tableName, this.params.columns)
            //         applyTopFor2ndHeader(tableName)
            //     }, 100)
            // }
        })
    }
}

// Expose EditableTable3 to the global window object
;(window as any).EditableTable3 = EditableTable3
