import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'
import { Dropdown4Edit } from './Dropdown4Edit'
import { Dropdown4View } from './Dropdown4View'

export class Dropdown4 {
    constructor(private params: TableRendererParams) {}

    render(): TableRenderedValueObject {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new Dropdown4Edit(this.params).render()
            default:
                return new Dropdown4View(this.params).render()
        }
    }
}
