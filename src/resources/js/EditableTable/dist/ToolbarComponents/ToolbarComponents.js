"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.ToolbarComponents = exports.ToolbarComponentList = void 0;
const HelloWorld_1 = require("./HelloWorld");
const MasterDuplicateDelete_1 = require("./MasterDuplicateDelete");
const Paginator_1 = require("./Paginator");
const PerPage_1 = require("./PerPage");
const TotalItems_1 = require("./TotalItems");
const TotalItems_Paginator_1 = require("./TotalItems_Paginator");
const TotalItems_Paginator_PerPage_1 = require("./TotalItems_Paginator_PerPage");
exports.ToolbarComponentList = {
    helloWorld: HelloWorld_1.HelloWorld,
    masterDuplicateDelete: MasterDuplicateDelete_1.MasterDuplicateDelete,
    totalItems_Paginator_PerPage: TotalItems_Paginator_PerPage_1.TotalItems_Paginator_PerPage,
    totalItems_Paginator: TotalItems_Paginator_1.TotalItems_Paginator,
    perPage: PerPage_1.PerPage,
    totalItems: TotalItems_1.TotalItems,
    paginator: Paginator_1.Paginator,
};
class ToolbarComponents {
    constructor(params) {
        this.params = params;
    }
    getRealClass(componentName) {
        if (exports.ToolbarComponentList[componentName]) {
            const theClass = exports.ToolbarComponentList[componentName];
            // console.log('ToolbarComponent.render()', componentName, theClass)
            return new theClass(this.params);
        }
        else {
            console.error(`Unknown component [${componentName}]`);
            return new HelloWorld_1.HelloWorld(this.params);
        }
    }
    static hasAnyTopComponent(params) {
        const { tableConfig } = params;
        return (tableConfig.topLeftControl ||
            tableConfig.topCenterControl ||
            tableConfig.topRightControl);
    }
    static hasAnyBottomComponent(params) {
        const { tableConfig } = params;
        return (tableConfig.bottomLeftControl ||
            tableConfig.bottomCenterControl ||
            tableConfig.bottomRightControl);
    }
    static register(params) {
        const { topLeftControl: tl, topCenterControl: tc, topRightControl: tr, bottomCenterControl: bc, bottomLeftControl: bl, bottomRightControl: br, } = params.tableConfig;
        if (tl) {
            const tmp = new ToolbarComponents(params).getRealClass(tl);
            $(`#${params.tableName}__Toolbar_Top_Left`).html(tmp.render());
            tmp.applyPostRenderScript();
        }
        if (tc) {
            const tmp = new ToolbarComponents(params).getRealClass(tc);
            $(`#${params.tableName}__Toolbar_Top_Center`).html(tmp.render());
            tmp.applyPostRenderScript();
        }
        if (tr) {
            const tmp = new ToolbarComponents(params).getRealClass(tr);
            $(`#${params.tableName}__Toolbar_Top_Right`).html(tmp.render());
            tmp.applyPostRenderScript();
        }
        if (bc) {
            const tmp = new ToolbarComponents(params).getRealClass(bc);
            $(`#${params.tableName}__Toolbar_Bottom_Center`).html(tmp.render());
            tmp.applyPostRenderScript();
        }
        if (bl) {
            const tmp = new ToolbarComponents(params).getRealClass(bl);
            $(`#${params.tableName}__Toolbar_Bottom_Left`).html(tmp.render());
            tmp.applyPostRenderScript();
        }
        if (br) {
            const tmp = new ToolbarComponents(params).getRealClass(br);
            $(`#${params.tableName}__Toolbar_Bottom_Right`).html(tmp.render());
            tmp.applyPostRenderScript();
        }
    }
}
exports.ToolbarComponents = ToolbarComponents;
//# sourceMappingURL=ToolbarComponents.js.map