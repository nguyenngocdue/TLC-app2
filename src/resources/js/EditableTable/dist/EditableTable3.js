"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const EditableTable3THead_1 = require("./EditableTable3THead");
const EditableTable3TFoot_1 = require("./EditableTable3TFoot");
const EditableTable3ToolbarTop_1 = require("./EditableTable3ToolbarTop");
const EditableTable3DefaultValue_1 = require("./EditableTable3DefaultValue");
const EditableTable3DefaultValue_2 = require("./EditableTable3DefaultValue");
const EditableTable3ColGroup_1 = require("./EditableTable3ColGroup");
const EditableTable3THead2nd_1 = require("./EditableTable3THead2nd");
const updateVirtualTableVisibleRows_1 = require("./VirtualScrolling/updateVirtualTableVisibleRows");
const EditableTable3FixedColumn_1 = require("./FixedColumn/EditableTable3FixedColumn");
const EditableTable3VirtualScrolling_1 = require("./VirtualScrolling/EditableTable3VirtualScrolling");
const ControlButtonGroup_1 = require("./ControlButtonGroup/ControlButtonGroup");
const TableConfigDiv_1 = require("./DebugDiv/TableConfigDiv");
const TableManipulations_1 = require("./Functions/TableManipulations");
const EnvConfigGroup_1 = require("./EnvConfigGroup/EnvConfigGroup");
const MasterCheckbox_1 = require("./Renderer/IdAction/MasterCheckbox");
const EditableTable3ToolbarBottom_1 = require("./EditableTable3ToolbarBottom");
const ToolbarComponents_1 = require("./ToolbarComponents/ToolbarComponents");
class EditableTable3 {
    constructor(params) {
        this.params = params;
        this.tableDebug = false;
        // private startTime = new Date().getTime()
        this.uploadServiceEndpoint = '/upload-service-endpoint';
        this.tableName = '';
        // console.log('EditableTable3.constructor()')
        this.tableDebug = params.tableConfig.tableDebug || false;
        this.tableName = params.tableName;
        if (this.tableDebug)
            console.log(`┌──────────────────${params.tableName}──────────────────┐`);
        //Columns
        tableColumns[params.tableName] = (0, EditableTable3DefaultValue_1.makeUpDefaultValue)(params);
        const columns = tableColumns[params.tableName];
        // console.log(this.params.columns)
        params.indexedColumns = {};
        if (columns) {
            columns.forEach((column) => {
                params.indexedColumns[column.dataIndex] = column;
            });
        }
        if (!params.tableConfig)
            params.tableConfig = {
                entityLineType: 'no-entityLineType',
            };
        if (!params.tableConfig.uploadServiceEndpoint)
            params.tableConfig.uploadServiceEndpoint = this.uploadServiceEndpoint;
        if (Array.isArray(tableData[this.tableName])) {
            const arrayOfAny = tableData[this.tableName];
            tableData[this.tableName] = (0, EditableTable3DefaultValue_2.convertArrayToLengthAware)(arrayOfAny);
            if (this.tableDebug)
                console.log('convertArrayToLengthAware', tableData[this.tableName]);
        }
        if (params.tableConfig.showNo)
            columns.unshift(EditableTable3DefaultValue_2.ColumnNoValue);
        // makeUpPaginator(params.tableConfig, params.dataSource)
        if (this.tableDebug)
            console.log('EditableTable3', Object.assign(Object.assign({}, params), { columns }));
        updateVirtualTableVisibleRows_1.visibleRowIds[params.tableName] = new Set();
        const { tableName } = this.params;
        const divId = `#${tableName}`;
        const div = document.querySelector(divId);
        if (!div)
            console.error(`EditableTable3: <div id="${tableName}"></div> is not found`);
    }
    renderTable() {
        const { tableName, tableConfig } = this.params;
        const columns = tableColumns[tableName];
        if (!columns) {
            const divId = `#${tableName}`;
            const div = document.querySelector(divId);
            const editableTable = `<div class=" text-center rounded m-1 p-2 bg-yellow-400 text-red-500">
                Columns is required
            </div>`;
            div && (div.innerHTML = editableTable);
        }
        const tableDebug = tableConfig.tableDebug || false;
        const borderColor = tableConfig.borderColor || `border-gray-300`;
        const borderT = ToolbarComponents_1.ToolbarComponents.hasAnyTopComponent(this.params)
            ? `border-t ${borderColor}`
            : 'rounded-t-lg';
        let tableWidth = 'width: 100%;';
        if (tableConfig.tableTrueWidth)
            tableWidth = `width: ${(0, EditableTable3ColGroup_1.calTableTrueWidth)(this.params)}px;`;
        const styleMaxH = tableConfig.maxH ? `max-height: ${tableConfig.maxH}px;` : '';
        const toolbarTop = (0, EditableTable3ToolbarTop_1.makeToolBarTop)(this.params);
        const toolbarBottom = (0, EditableTable3ToolbarBottom_1.makeToolBarBottom)(this.params);
        const tableHeader = tableConfig.tableHeader
            ? `<div component="tableHeader">${tableConfig.tableHeader}</div>`
            : '';
        const tableFooter = tableConfig.tableFooter
            ? `<div component="tableFooter">${tableConfig.tableFooter}</div>`
            : '';
        if (this.tableDebug)
            console.log('Start to make Tbody');
        // const trs = new TbodyTrs(this.params).render()
        // if (this.tableDebug) console.log('Start to make Colgroup')
        const colgroupStr = (0, EditableTable3ColGroup_1.makeColGroup)(this.params);
        // if (this.tableDebug) console.log('Start to make Thead')
        const tHeadStr = (0, EditableTable3THead_1.makeThead)(this.params);
        // if (this.tableDebug) console.log('Start to make Thead2nd')
        const tHead2ndStr = (0, EditableTable3THead2nd_1.makeThead2nd)(this.params);
        // if (this.tableDebug) console.log('Start to make Tfoot')
        const tFootStr = (0, EditableTable3TFoot_1.makeTfoot)(this.params);
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
        </table>`;
        const classList = `table-wrp block bg-gray-100 overflow-x-auto ${borderT} border-l border-r border-b`;
        const styleList = `${styleMaxH}`;
        const wrappingDiv = `<div id="${tableName}__container" class="${classList}" style="${styleList}">
            ${tableStr}
        </div>`;
        const debugStrTop = tableDebug
            ? `<div class="bg-red-600 text-white text-center border font-bold">${tableName} is in DEBUG Mode</div>`
            : ``;
        console.log('this.params.tableConfig', this.params.tableConfig);
        const debugStrBottom = tableDebug
            ? `<div class="bg-red-600 text-white border font-bold">
                ${(0, TableConfigDiv_1.TableConfigDiv)(this.params)}
                </div>`
            : ``;
        const controlButtonGroup = `<div id="${tableName}__control_button_group"></div>`;
        const evnConfig = `<div id="${tableName}__env_config_group"></div>`;
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
        `;
        if (this.tableDebug)
            console.log('madeEmptyEditableTable Body');
        return editableTable;
    }
    render() {
        const { tableName } = this.params;
        const divId = `#${tableName}`;
        const div = document.querySelector(divId);
        if (!div)
            return '';
        const columns = tableColumns[tableName];
        const dataSource = tableData[tableName];
        let body = `<tr><td class='text-center h-40 text-gray-500 border' colspan='100%'>No Data</td></tr>`;
        if (!columns) {
            body = `<div class=" text-center rounded m-1 p-2 bg-yellow-400 text-red-500">
            Columns is required
            </div>`;
        }
        if (columns && !dataSource) {
            body = `<div class=" text-center rounded m-1 p-2 bg-yellow-400 text-red-500">
            DataSource is required
            </div>`;
        }
        // let trs: HTMLTableRowElement[] = []
        if (columns && dataSource) {
            const tableEmptyRows = this.renderTable();
            if (tableEmptyRows)
                body = tableEmptyRows;
        }
        div && (div.innerHTML = body);
        if (this.tableDebug) {
            console.log(`└──────────────────${this.params.tableName}──────────────────┘`);
            console.log('');
        }
        // const endTime00 = new Date().getTime()
        // console.log('EditableTable3.render() took', endTime00 - this.startTime, 'ms')
        //when document is ready
        document.addEventListener('DOMContentLoaded', () => {
            //Wait sometime for the browser to finish rendering the table
            if (dataSource) {
                ToolbarComponents_1.ToolbarComponents.register(this.params);
                (0, MasterCheckbox_1.registerOnClickMasterCB)(tableName);
                (0, EditableTable3VirtualScrolling_1.applyVirtualScrolling)(this.params);
                (0, TableManipulations_1.replaceDivWith)(tableName, 'control_button_group', (0, ControlButtonGroup_1.ControlButtonGroup)(this.params));
                if (this.tableDebug) {
                    (0, TableManipulations_1.replaceDivWith)(tableName, 'env_config_group', (0, EnvConfigGroup_1.EnvConfigGroup)(this.params));
                }
                setTimeout(() => (0, EditableTable3FixedColumn_1.applyTopFor2ndHeader)(tableName), 100);
            }
        });
    }
}
exports.default = EditableTable3;
window.EditableTable3 = EditableTable3;
//# sourceMappingURL=EditableTable3.js.map