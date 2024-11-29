import { TableParams } from '../Type/EditableTable3ParamType'
import { HelloWorld } from './HelloWorld'
import { MasterDuplicateDelete } from './MasterDuplicateDelete'
import { Paginator } from './Paginator'
import { PerPage } from './PerPage'
import { TotalItems } from './TotalItems'
import { TotalItems_Paginator } from './TotalItems_Paginator'
import { TotalItems_Paginator_PerPage } from './TotalItems_Paginator_PerPage'

export const ToolbarComponentList = {
    helloWorld: HelloWorld,

    masterDuplicateDelete: MasterDuplicateDelete,

    totalItems_Paginator_PerPage: TotalItems_Paginator_PerPage,
    totalItems_Paginator: TotalItems_Paginator,
    perPage: PerPage,
    totalItems: TotalItems,
    paginator: Paginator,
}

export class ToolbarComponents {
    private params: TableParams
    constructor(params: TableParams) {
        this.params = params
    }

    getRealClass(componentName: keyof typeof ToolbarComponentList) {
        if (ToolbarComponentList[componentName]) {
            const theClass = ToolbarComponentList[componentName]
            // console.log('ToolbarComponent.render()', componentName, theClass)
            return new theClass(this.params)
        } else {
            console.error(`Unknown component [${componentName}]`)
            return new HelloWorld(this.params)
        }
    }

    static hasAnyTopComponent(params: TableParams) {
        const { tableConfig } = params
        return (
            tableConfig.topLeftControl ||
            tableConfig.topCenterControl ||
            tableConfig.topRightControl
        )
    }

    static hasAnyBottomComponent(params: TableParams) {
        const { tableConfig } = params
        return (
            tableConfig.bottomLeftControl ||
            tableConfig.bottomCenterControl ||
            tableConfig.bottomRightControl
        )
    }

    static register(params: TableParams) {
        const {
            topLeftControl: tl,
            topCenterControl: tc,
            topRightControl: tr,
            bottomCenterControl: bc,
            bottomLeftControl: bl,
            bottomRightControl: br,
        } = params.tableConfig

        if (tl) {
            const tmp = new ToolbarComponents(params).getRealClass(tl)
            $(`#${params.tableName}__Toolbar_Top_Left`).html(tmp.render())
            tmp.applyPostRenderScript()
        }
        if (tc) {
            const tmp = new ToolbarComponents(params).getRealClass(tc)
            $(`#${params.tableName}__Toolbar_Top_Center`).html(tmp.render())
            tmp.applyPostRenderScript()
        }
        if (tr) {
            const tmp = new ToolbarComponents(params).getRealClass(tr)
            $(`#${params.tableName}__Toolbar_Top_Right`).html(tmp.render())
            tmp.applyPostRenderScript()
        }
        if (bc) {
            const tmp = new ToolbarComponents(params).getRealClass(bc)
            $(`#${params.tableName}__Toolbar_Bottom_Center`).html(tmp.render())
            tmp.applyPostRenderScript()
        }
        if (bl) {
            const tmp = new ToolbarComponents(params).getRealClass(bl)
            $(`#${params.tableName}__Toolbar_Bottom_Left`).html(tmp.render())
            tmp.applyPostRenderScript()
        }
        if (br) {
            const tmp = new ToolbarComponents(params).getRealClass(br)
            $(`#${params.tableName}__Toolbar_Bottom_Right`).html(tmp.render())
            tmp.applyPostRenderScript()
        }
    }
}
