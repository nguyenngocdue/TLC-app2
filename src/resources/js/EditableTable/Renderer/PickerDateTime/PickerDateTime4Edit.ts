import flatpickr from 'flatpickr'

import { Renderer4Edit } from '../Renderer4Edit'
import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { TableColumnPickerDateTime } from '../../Type/EditableTable3ColumnType'
import { defaultWidth } from './Shared'

declare global {
    interface HTMLInputElement {
        _flatpickr?: flatpickr.Instance
    }
}

export class PickerDateTime4Edit extends Renderer4Edit {
    protected tableDebug = false

    getConfigJson(pickerType: string) {
        const defaultConfig = {
            altInput: true,
            weekNumbers: true,
            time_24hr: true,
            allowInput: true,
        }

        switch (pickerType) {
            case 'datetime':
                return {
                    ...defaultConfig,
                    enableTime: true,
                    altFormat: 'd/m/Y H:i',
                    dateFormat: 'Y-m-d H:i:S',
                }
            case 'date':
                return {
                    ...defaultConfig,
                    enableTime: false,
                    altFormat: 'd/m/Y',
                    dateFormat: 'Y-m-d',
                }
            case 'time':
                return {
                    ...defaultConfig,
                    enableTime: true,
                    noCalendar: true,
                    altFormat: 'H:i',
                    dateFormat: 'H:i:S',
                }
            case 'month':
                return {
                    ...defaultConfig,
                    enableTime: false,
                    noCalendar: false,
                    altFormat: 'm/Y',
                    dateFormat: 'Y-m',
                }
            case 'year':
                return {
                    ...defaultConfig,
                    enableTime: false,
                    noCalendar: false,
                    altFormat: 'Y',
                    dateFormat: 'Y',
                }
            default:
                throw new Error(`Unsupported picker type: ${pickerType}`)
        }
    }

    applyPostRenderScript(): void {
        const column = this.column as TableColumnPickerDateTime
        const pickerType = column.rendererAttrs?.pickerType || 'datetime'
        const element = document.getElementById(this.controlId) as HTMLInputElement

        if (element) flatpickr(element, this.getConfigJson(pickerType))
    }

    control() {
        const tableConfig = this.tableConfig
        const column = this.column as TableColumnPickerDateTime
        const pickerType = column.rendererAttrs?.pickerType || 'datetime'

        return `<input 
            type="text" 
            
            id="${this.controlId}" 
            class="${tableConfig.classList?.text}" 
            placeholder="${pickerType} input"
        >`
    }

    render(): TableRenderedValueObject {
        const column = this.column as TableColumnPickerDateTime
        const pickerType = column.rendererAttrs?.pickerType || 'datetime'

        let divStyle: { [key: string]: string | number } = {}
        divStyle['width'] = defaultWidth(pickerType) + 16 + 'px'

        let tdStyle: { [key: string]: string | number } = {}
        tdStyle['width'] = defaultWidth(pickerType) + 16 + 'px'

        return {
            rendered: this.control(),
            // tdStyle,
            // divStyle,
            applyPostRenderScript: this.applyPostRenderScript.bind(this),
            // applyOnMouseMoveScript: this.applyOnMouseMoveScript.bind(this),
        }
    }
}
