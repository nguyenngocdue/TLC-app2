import { DataFormat } from 'select2'

import { Str, getDataSource } from '../../Functions'
import { TableColumnDropdown } from '../../Type/EditableTable3ColumnType'
import { Renderer4Edit } from '../Renderer4Edit'

export class Dropdown4Edit extends Renderer4Edit {
    protected tableDebug = false

    getOptionsExpensive = (column: TableColumnDropdown) => {
        const { rendererAttrs = {} } = column
        const cbbDataSource = getDataSource(column)

        const {
            valueField = 'id',
            labelField = 'name',
            // descriptionField = 'description',
            // tooltipField = '',
        } = rendererAttrs

        const options = Object.keys(cbbDataSource).map((key) => {
            const item = cbbDataSource[key]
            // const tooltip = item[tooltipField] || Str.makeId(item[valueField])

            const option: DataFormat = {
                id: item[valueField],
                text: item[labelField] as string,
            }
            return option
            // return `<option value="${item[valueField]}" title="${tooltip}">
            //     ${item[labelField]}
            // </option>`
        })
        return options
    }

    getOptionsCheap = (column: TableColumnDropdown) => {
        const cellValue = this.cellValue as unknown as string
        const { rendererAttrs = {} } = column
        const cbbDataSource = getDataSource(column)
        const selectedItem = cbbDataSource[cellValue]

        const {
            valueField = 'id',
            labelField = 'name',
            // descriptionField = 'description',
            tooltipField = valueField,
        } = rendererAttrs

        if (!selectedItem) return ``
        const item = cbbDataSource[selectedItem[valueField]]
        const tooltip = item[tooltipField] || Str.makeId(item[valueField])
        // const fakeOption = `<option value="-1" title="Fake Option">-1</option>`
        const realOption = `<option value="${item[valueField]}" title="${tooltip}">
            ${item[labelField]}
        </option>`
        return realOption
    }

    applyOnChangeScript(): void {
        $('#' + this.controlId).on('change', () => this.setValueToTableData())
    }

    applyOnMouseMoveScript(): void {
        // console.log('Dropdown4Edit.applyOnMouseMoveScript()')
        const dropdown = $('#' + this.controlId)
        if (!dropdown.data('select2')) {
            const column = this.column as TableColumnDropdown
            const options = this.getOptionsExpensive(column)

            dropdown.select2({ data: options })
            this.applyOnChangeScript()
        } else {
            // console.log('Dropdown4Edit.applyOnMouseMoveScript() - select2 already initialized')
        }
    }

    control() {
        const classList = this.tableConfig.classList?.dropdown_fake
        // return this.cellValue as unknown as string
        const column = this.column as TableColumnDropdown
        // console.log(dataIndex, cellValue, valueField, cbbDataSource)

        // const options = this.getOptions(column)
        const optionsStr = this.getOptionsCheap(column)

        // <option class="text-gray-300" value="" disabled selected>Select an option</option>
        return `<select 
            id="${this.controlId}"             
            class="${classList} no-arrow"
            >
            ${optionsStr}
        </select>`
    }
}
