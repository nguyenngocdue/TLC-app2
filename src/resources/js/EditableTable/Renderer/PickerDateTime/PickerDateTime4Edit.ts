import flatpickr from 'flatpickr'

import { Renderer4Edit } from '../Renderer4Edit'
import { TableColumnPickerDateTime } from '../../Type/EditableTable3ColumnType'
import { TableCellType } from '../../Type/EditableTable3DataLineType'
import { getConfigFormat, getConfigJson } from './Shared'
import moment from 'moment'

export class PickerDateTime4Edit extends Renderer4Edit {
    protected tableDebug = false

    applyOnChangeScript(): void {
        const column = this.column as TableColumnPickerDateTime
        const pickerType = column.rendererAttrs?.pickerType || 'datetime'
        const element = document.getElementById(this.controlId) as HTMLInputElement
        let date = ''
        if (element) {
            // console.log('PickerDateTime4Edit.applyOnChangeScript', element)
            element.addEventListener('change', () => {
                let value = element.value
                const format = getConfigFormat(pickerType)
                switch (pickerType) {
                    case 'datetime':
                        value = moment(value).utc().format()
                        date = moment.utc(value).format(format)
                        break
                    case 'date':
                    case 'month':
                    case 'year':
                        date = moment.utc(value).format(format)
                        break
                    case 'time':
                        //Do nothing
                        date = value
                        break
                }

                // console.log(pickerType, value, date)

                this.setValueToTableData(date as unknown as TableCellType)
            })
        }
    }

    applyPostRenderScript(): void {
        const column = this.column as TableColumnPickerDateTime
        const pickerType = column.rendererAttrs?.pickerType || 'datetime'
        const element = document.getElementById(this.controlId) as HTMLInputElement
        const value = this.cellValue as unknown as string

        if (element) {
            const config = getConfigJson(pickerType)
            switch (pickerType) {
                case 'datetime':
                    config.defaultDate = moment.utc(value).local().toDate()
                    break
                case 'time':
                    config.defaultDate = moment(
                        '1970-01-01T' + value,
                        'YYYY-MM-DDTHH:mm:ss',
                    ).toDate()
                    break
                default:
                    config.defaultDate = moment.utc(value).toDate()
            }

            flatpickr(element, config)
        }
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
