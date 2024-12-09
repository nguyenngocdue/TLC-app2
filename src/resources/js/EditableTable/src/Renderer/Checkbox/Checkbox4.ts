import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'
import { Boolean4View } from '../Boolean/Boolean4View'
import { Checkbox4Edit } from './Checkbox4Edit'

export class Checkbox4 {
    constructor(private params: TableRendererParams) {}

    render(): TableRenderedValueObject {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new Checkbox4Edit(this.params).render()
            default:
                return new Boolean4View(this.params).render()
        }
    }
}
