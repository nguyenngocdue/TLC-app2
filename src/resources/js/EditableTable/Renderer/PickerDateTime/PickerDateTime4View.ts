import moment from 'moment'
import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { TableColumnPickerDateTime } from '../../Type/EditableTable3ColumnType'
import { Renderer4View } from '../Renderer4View'

export class PickerDateTime4View extends Renderer4View {
    render(): TableRenderedValueObject {
        const value = this.cellValue as unknown as string
        const column = this.column as TableColumnPickerDateTime

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

        const classStr = `text-center`
        return { rendered, tdClass: classStr }
    }
}
