import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'
import { Number4Edit } from './Number4Edit'
import { Number4View } from './Number4View'

export class Number4 {
    constructor(private params: TableRendererParams) {}

    render(): TableRenderedValueObject {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new Number4Edit(this.params).render()
            default:
                return new Number4View(this.params).render()
        }
    }
}
