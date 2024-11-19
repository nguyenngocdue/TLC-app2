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
        let divStyle: { [key: string]: string | number } = {}
        //To hide "Invalid date" message
        if (this.cellValue) {
            switch (pickerType) {
                case 'datetime':
                    rendered = moment(value).format('DD/MM/YYYY HH:mm')
                    divStyle['width'] = '120px'
                    break
                case 'date':
                    rendered = moment(value).format('DD/MM/YYYY')
                    divStyle['width'] = '80px'
                    break
                case 'time':
                    rendered = moment(value).format('HH:mm')
                    divStyle['width'] = '50px'
                    break
                case 'month':
                    rendered = moment(value).format('MM/YYYY')
                    divStyle['width'] = '60px'
                    break
                case 'year':
                    rendered = moment(value).format('YYYY')
                    divStyle['width'] = '50px'
                    break
                default:
                    rendered = 'unknown how to render pickerType: ' + pickerType
                    break
            }
        }

        const tdClass = `text-center`
        return { rendered, tdClass, divStyle }
    }
}
