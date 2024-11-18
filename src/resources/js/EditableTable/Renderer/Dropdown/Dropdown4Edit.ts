import { DataFormat } from 'select2'

import { Str } from '../../Function/Functions'
import { getDataSource } from '../../Function/CacheKByKey'
import { TableColumnDropdown } from '../../Type/EditableTable3ColumnType'
import { Renderer4Edit } from '../Renderer4Edit'

export class Dropdown4Edit extends Renderer4Edit {
    protected tableDebug = false

    static getOptionsExpensive = (column: TableColumnDropdown) => {
        const { rendererAttrs = {} } = column
        const cbbDataSource = getDataSource(column)

        const {
            valueField = 'id',
            labelField = 'name',
            // descriptionField = 'description',
            tooltipField = '',
        } = rendererAttrs

        const options = Object.keys(cbbDataSource).map((key) => {
            const item = cbbDataSource[key]
            const tooltip = item[tooltipField] || Str.makeId(item[valueField])

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
        return `<option value="${item[valueField]}" title="${tooltip}">
            ${item[labelField]}
        </option>`
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
            name="${this.controlName}" 
            class="${classList}"
            >
            ${optionsStr}
        </select>`
    }

    // applyPostScript = () => {
    //     // console.log('Dropdown4Edit.applyPostScript()', this)
    //     const column = this.column as TableColumnDropdown
    //     const { rendererAttrs = {}, dataIndex } = column
    //     const {
    //         allowClear,
    //         allowChooseWhenOneItem,
    //         allowOpen,
    //         valueField = 'id',
    //         labelField = 'name',
    //         // descriptionField = 'description',
    //         tooltipField = '',
    //     } = rendererAttrs
    //     // $(`#${this.controlId}`).select2({
    //     //     placeholder: 'Select an option',
    //     //     allowClear,
    //     // })
    // }

    render() {
        let result = this.control()

        return {
            rendered: result,
            classStr: this.column.classList || '',
            // applyPostScript: this.applyPostScript,
        }
    }
}
