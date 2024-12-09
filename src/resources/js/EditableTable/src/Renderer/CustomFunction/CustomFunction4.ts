import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'
import { CustomFunction4Edit } from './CustomFunction4Edit'
import { CustomFunction4View } from './CustomFunction4View'

export class CustomFunction4 {
    constructor(private params: TableRendererParams) {}
    render(): TableRenderedValueObject {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new CustomFunction4Edit(this.params).render()
            default:
                return new CustomFunction4View(this.params).render()
        }
    }
}
