import { TableRendererParams } from '../../Type/EditableTable3DataLineType'
import { Text4Edit } from './Text4Edit'
import { Text4View } from './Text4View'

export class Text4 {
    constructor(private params: TableRendererParams) {}

    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new Text4Edit(this.params).render()
            default:
                return new Text4View(this.params).render()
        }
    }
}
