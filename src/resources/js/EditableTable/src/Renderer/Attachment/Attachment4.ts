import { TableRendererParams } from '../../Type/EditableTable3DataLineType'
import { Thumbnail4View } from '../Thumbnail/Thumbnail4View'
import { Attachment4Edit } from './Attachment4Edit'

export class Attachment4 {
    constructor(private params: TableRendererParams) {}

    render() {
        switch (true) {
            case this.params.column.mode == 'edit':
            case this.params.column.editable: // this line will be removed for new flexible MODE
                return new Attachment4Edit(this.params).render()
            default:
                return new Thumbnail4View(this.params).render()
        }
    }
}
