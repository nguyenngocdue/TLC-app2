import { TableParams } from './Type/EditableTable3ParamType'

import { makeThead } from './EditableTable3THead'
import { makeTfoot } from './EditableTable3TFoot'
import { makeToolBarTop } from './EditableTable3ToolbarTop'
import { makeUpDefaultValue } from './EditableTable3DefaultValue'
import { ColumnNoValue, convertArrayToLengthAware } from './EditableTable3DefaultValue'
import { calTableTrueWidth, makeColGroup } from './EditableTable3ColGroup'
import { makeThead2nd } from './EditableTable3THead2nd'
import { visibleRowIds } from './VirtualScrolling/updateVirtualTableVisibleRows'
import { applyTopFor2ndHeader } from './FixedColumn/EditableTable3FixedColumn'
import { applyVirtualScrolling } from './VirtualScrolling/EditableTable3VirtualScrolling'
import { LengthAware } from './Type/EditableTable3DataLineType'
import { TableColumn } from './Type/EditableTable3ColumnType'
import { ControlButtonGroup } from './ControlButtonGroup/ControlButtonGroup'
import { TableConfigDiv } from './DebugDiv/TableConfigDiv'
import { replaceDivWith } from './Functions/TableManipulations'
import { EnvConfigGroup } from './EnvConfigGroup/EnvConfigGroup'
import { registerOnClickMasterCB } from './Renderer/IdAction/MasterCheckbox'
import { makeToolBarBottom } from './EditableTable3ToolbarBottom'
import { ToolbarComponents } from './ToolbarComponents/ToolbarComponents'

declare let tableData: { [tableName: string]: LengthAware | any[] }
declare let tableColumns: { [tableName: string]: TableColumn[] }

class EditableTable3 {
    private tableDebug = false
    // private startTime = new Date().getTime()
    private uploadServiceEndpoint = '/upload-service-endpoint'
    private tableName: string = ''

    constructor(private params: TableParams) {
        // console.log('EditableTable3.constructor()')
        this.tableDebug = params.tableConfig.tableDebug || false
        this.tableName = params.tableName
        if (this.tableDebug)
            console.log(`┌──────────────────${params.tableName}──────────────────┐`)

        //Columns
        tableColumns[params.tableName] = makeUpDefaultValue(params)
        const columns = tableColumns[params.tableName]
        // console.log(this.params.columns)
        params.indexedColumns = {}
        if (columns) {
            columns.forEach((column) => {
                params.indexedColumns[column.dataIndex] = column
            })
        }

        if (!params.tableConfig)
            params.tableConfig = {
                entityLineType: 'no-entityLineType',
            }
        if (!params.tableConfig.uploadServiceEndpoint)
            params.tableConfig.uploadServiceEndpoint = this.uploadServiceEndpoint
        if (Array.isArray(tableData[this.tableName])) {
            const arrayOfAny = tableData[this.tableName] as any[]
            tableData[this.tableName] = convertArrayToLengthAware(arrayOfAny)
            if (this.tableDebug) console.log('convertArrayToLengthAware', tableData[this.tableName])
        }
        if (params.tableConfig.showNo) columns.unshift(ColumnNoValue)
        // makeUpPaginator(params.tableConfig, params.dataSource)

        if (this.tableDebug) console.log('EditableTable3', { ...params, columns })

        visibleRowIds[params.tableName] = new Set<string>()
    }

    renderTable() {
        const { tableName, tableConfig } = this.params
        const columns = tableColumns[tableName]

        if (!columns) {
            const divId = `#${tableName}`
            const div = document.querySelector(divId)
            const editableTable = `<div class=" text-center rounded m-1 p-2 bg-yellow-400 text-red-500">
                Columns is required
            </div>`
            div && (div.innerHTML = editableTable)
        }

        const tableDebug = tableConfig.tableDebug || false
        const borderColor = tableConfig.borderColor || `border-gray-300`
        const borderT = ToolbarComponents.hasAnyTopComponent(this.params)
            ? `border-t ${borderColor}`
            : 'rounded-t-lg'

        let tableWidth = 'width: 100%;'
        if (tableConfig.tableTrueWidth) tableWidth = `width: ${calTableTrueWidth(this.params)}px;`

        const styleMaxH = tableConfig.maxH ? `max-height: ${tableConfig.maxH}px;` : ''

        const toolbarTop = makeToolBarTop(this.params)
        const toolbarBottom = makeToolBarBottom(this.params)

        const tableHeader = tableConfig.tableHeader
            ? `<div component="tableHeader">${tableConfig.tableHeader}</div>`
            : ''
        const tableFooter = tableConfig.tableFooter
            ? `<div component="tableFooter">${tableConfig.tableFooter}</div>`
            : ''

        if (this.tableDebug) console.log('Start to make Tbody')
        // const trs = new TbodyTrs(this.params).render()

        // if (this.tableDebug) console.log('Start to make Colgroup')
        const colgroupStr = makeColGroup(this.params)
        // if (this.tableDebug) console.log('Start to make Thead')
        const tHeadStr = makeThead(this.params)
        // if (this.tableDebug) console.log('Start to make Thead2nd')
        const tHead2ndStr = makeThead2nd(this.params)
        // if (this.tableDebug) console.log('Start to make Tfoot')
        const tFootStr = makeTfoot(this.params)

        const tableStr = `<table 
                id="${tableName}__table"
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
                <tr id="spacer-bottom"></tr>
            </tbody>

            <tfoot>
                ${tFootStr}
            </tfoot>
        </table>`

        const classList = `table-wrp block bg-gray-100 overflow-x-auto ${borderT} border-l border-r border-b`
        const styleList = `${styleMaxH}`

        const wrappingDiv = `<div id="${tableName}__container" class="${classList}" style="${styleList}">
            ${tableStr}
        </div>`

        const debugStrTop = tableDebug
            ? `<div class="bg-red-600 text-white text-center border font-bold">${tableName} is in DEBUG Mode</div>`
            : ``

        console.log('this.params.tableConfig', this.params.tableConfig)
        const debugStrBottom = tableDebug
            ? `<div class="bg-red-600 text-white border font-bold">
                ${TableConfigDiv(this.params)}
                </div>`
            : ``

        const controlButtonGroup = `<div id="${tableName}__control_button_group"></div>`
        const evnConfig = `<div id="${tableName}__env_config_group"></div>`

        const editableTable = `
        ${debugStrTop}
        ${tableHeader}
        ${toolbarTop}
        ${wrappingDiv}
        ${toolbarBottom}
        ${tableFooter}
        ${controlButtonGroup}
        ${evnConfig}
        ${debugStrBottom}
        `

        if (this.tableDebug) console.log('madeEmptyEditableTable Body')

        return editableTable
    }

    render() {
        const { tableName } = this.params
        const columns = tableColumns[tableName]
        const dataSource = tableData[tableName] as LengthAware

        let body = `<tr><td class='text-center h-40 text-gray-500 border' colspan='100%'>No Data</td></tr>`

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

        // let trs: HTMLTableRowElement[] = []
        if (columns && dataSource) {
            const tableEmptyRows = this.renderTable()
            if (tableEmptyRows) body = tableEmptyRows
        }

        const divId = `#${tableName}`
        const div = document.querySelector(divId)
        div && (div.innerHTML = body)

        if (this.tableDebug) {
            console.log(`└──────────────────${this.params.tableName}──────────────────┘`)
            console.log('')
        }

        // const endTime00 = new Date().getTime()
        // console.log('EditableTable3.render() took', endTime00 - this.startTime, 'ms')

        //when document is ready
        $(() => {
            //Wait sometime for the browser to finish rendering the table
            if (dataSource) {
                ToolbarComponents.register(this.params)
                registerOnClickMasterCB(tableName)
                applyVirtualScrolling(this.params)
                replaceDivWith(tableName, 'control_button_group', ControlButtonGroup(this.params))
                if (this.tableDebug) {
                    replaceDivWith(tableName, 'env_config_group', EnvConfigGroup(this.params))
                }
                setTimeout(() => applyTopFor2ndHeader(tableName), 100)
            }
        })
    }
}

// Expose EditableTable3 to the global window object
;(window as any).EditableTable3 = EditableTable3
