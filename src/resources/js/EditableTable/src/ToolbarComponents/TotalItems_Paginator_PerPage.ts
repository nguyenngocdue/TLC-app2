import { Paginator } from './Paginator'
import { PerPage } from './PerPage'
import { TotalItems } from './TotalItems'
import { ToolbarComponentParent } from './ToolbarComponentParent'

export class TotalItems_Paginator_PerPage extends ToolbarComponentParent {
    render() {
        const totalItems = new TotalItems(this.params)
        const paginator = new Paginator(this.params)
        const perPage = new PerPage(this.params)

        return `<div class="flex items-center gap-1">
            ${totalItems.render()}
            ${paginator.render()}
            ${perPage.render()}
        </div>`
    }
}
