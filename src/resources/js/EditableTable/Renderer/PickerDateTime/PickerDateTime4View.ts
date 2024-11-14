import moment from 'moment'
import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'
import { TableColumnPickerDateTime } from '../../Type/EditableTable3ColumnType'

export class PickerDateTime4View {
    constructor(private params: TableRendererParams) {}

    render(): TableRenderedValueObject {
        const value = this.params.cellValue as unknown as string
        const column = this.params.column as TableColumnPickerDateTime

        const pickerType = column.rendererAttrs?.pickerType || 'datetime'
        let rendered = ''
        switch (pickerType) {
            case 'datetime':
                rendered = moment(value).format('DD/MM/YYYY HH:mm')
                break
            case 'date':
                rendered = moment(value).format('DD/MM/YYYY')
                break
            case 'time':
                rendered = moment(value).format('HH:mm')
                break
            case 'month':
                rendered = moment(value).format('MM/YYYY')
                break
            case 'year':
                rendered = moment(value).format('YYYY')
                break
            default:
                rendered = 'unknown how to render pickerType: ' + pickerType
                break
        }

        const classStr = `${this.params.column.classList} text-center`
        return { rendered, classStr }
    }
}
