import flatpickr from 'flatpickr'

import { Renderer4Edit } from '../Renderer4Edit'
import { TableColumnPickerDateTime } from '../../Type/EditableTable3ColumnType'
import { getConfigJson } from './PickerTypeConfig'
import { TableCellType } from '../../Type/EditableTable3DataLineType'

export class PickerDateTime4Edit extends Renderer4Edit {
    protected tableDebug = false

    applyOnChangeScript(): void {
        // const column = this.column as TableColumnPickerDateTime
        // const pickerType = column.rendererAttrs?.pickerType || 'datetime'
        const element = document.getElementById(this.controlId) as HTMLInputElement

        if (element) {
            // console.log('PickerDateTime4Edit.applyOnChangeScript', element)
            element.addEventListener('change', () => {
                const value = element.value
                const date = new Date(value)
                const newValue = date.toISOString().slice(0, 19).replace('T', ' ')

                this.setValueToTableData(newValue as unknown as TableCellType)
            })
        }
    }

    applyPostRenderScript(): void {
        const column = this.column as TableColumnPickerDateTime
        const pickerType = column.rendererAttrs?.pickerType || 'datetime'
        const element = document.getElementById(this.controlId) as HTMLInputElement

        if (element) flatpickr(element, getConfigJson(pickerType))
    }

    control() {
        const tableConfig = this.tableConfig
        const column = this.column as TableColumnPickerDateTime
        const pickerType = column.rendererAttrs?.pickerType || 'datetime'

        return `<input 
            type="hidden" 
            id="${this.controlId}" 
            class="${tableConfig.classList?.text}" 
            placeholder="${pickerType} input"
            value="${this.cellValue}"
        >`
    }
}
