require=function e(t,o,c){function r(p,i){if(!o[p]){if(!t[p]){var a="function"==typeof require&&require;if(!i&&a)return a(p,!0);if(n)return n(p,!0);var d=new Error("Cannot find module '"+p+"'");throw d.code="MODULE_NOT_FOUND",d}var s=o[p]={exports:{}};t[p][0].call(s.exports,function(e){var o=t[p][1][e];return r(o||e)},s,s.exports,e,t,o,c)}return o[p].exports}for(var n="function"==typeof require&&require,p=0;p<c.length;p++)r(c[p]);return r}({HelloWorld:[function(e,t,o){"use strict";cc._RF.push(t,"280c3rsZJJKnZ9RqbALVwtK","HelloWorld"),cc.Class({extends:cc.Component,properties:{label:{default:null,type:cc.Label},text:"Hello, World!"},onLoad:function(){this.label.string=this.text},update:function(e){}}),cc._RF.pop()},{}],Quanju:[function(e,t,o){"use strict";cc._RF.push(t,"df756snyYBC+I8wD1md87gn","Quanju"),Object.defineProperty(o,"__esModule",{value:!0});var c=cc._decorator,r=c.ccclass,n=c.property,p=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.label=null,t.text="hello",t}return __extends(t,e),t.prototype.start=function(){},t.qw=0,__decorate([n(cc.Label)],t.prototype,"label",void 0),__decorate([n],t.prototype,"text",void 0),t=__decorate([r],t)}(cc.Component);o.default=p,cc._RF.pop()},{}],"场景":[function(e,t,o){"use strict";cc._RF.push(t,"fb7bb3RWRZGWamckiSkbD65","场景"),Object.defineProperty(o,"__esModule",{value:!0});var c=e("./Quanju"),r=cc._decorator,n=r.ccclass,p=r.property,i=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.label=null,t.text="hello",t.jk=0,t}return __extends(t,e),t.prototype.start=function(){},t.prototype.update=function(e){c.default.qw==this.jk&&(this.dj.active=!0)},t.prototype.kaishgi=function(){this.sp1.active=!0,this.sp2.active=!0,this.sp3.active=!0,this.sp4.active=!0,this.sp5.active=!0,this.sp6.active=!0,this.sp7.active=!0,this.sp8.active=!0,this.sp9.active=!1},t.prototype.zailai=function(){cc.director.loadScene(cc.director.getScene().name)},__decorate([p(cc.Label)],t.prototype,"label",void 0),__decorate([p],t.prototype,"text",void 0),__decorate([p(cc.Node)],t.prototype,"dj",void 0),__decorate([p],t.prototype,"jk",void 0),__decorate([p(cc.Node)],t.prototype,"sp1",void 0),__decorate([p(cc.Node)],t.prototype,"sp2",void 0),__decorate([p(cc.Node)],t.prototype,"sp3",void 0),__decorate([p(cc.Node)],t.prototype,"sp4",void 0),__decorate([p(cc.Node)],t.prototype,"sp5",void 0),__decorate([p(cc.Node)],t.prototype,"sp6",void 0),__decorate([p(cc.Node)],t.prototype,"sp7",void 0),__decorate([p(cc.Node)],t.prototype,"sp8",void 0),__decorate([p(cc.Node)],t.prototype,"sp9",void 0),t=__decorate([n],t)}(cc.Component);o.default=i,cc._RF.pop()},{"./Quanju":"Quanju"}],"拖动":[function(e,t,o){"use strict";cc._RF.push(t,"4f69epjuiJL0oDP1DoNltcu","拖动"),Object.defineProperty(o,"__esModule",{value:!0});var c=e("./Quanju"),r=cc._decorator,n=r.ccclass,p=r.property,i=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.label=null,t.text="hello",t.mouseb=!1,t.bb=!1,t}return __extends(t,e),t.prototype.start=function(){cc.director.getCollisionManager().enabled=!0},t.prototype.OnMouseDown=function(e){console.log("d"),this.mouseb=!0},t.prototype.OnMouseMove=function(e){1==this.mouseb&&0==this.bb&&(e.currentTarget.x=e.getLocationX(),e.currentTarget.y=e.getLocationY())},t.prototype.OnMouseUp=function(e){this.mouseb=!1,0==this.bb&&(this.node.x=this.xzz.x,this.node.y=this.xzz.y)},t.prototype.update=function(e){this.node.on(cc.Node.EventType.MOUSE_DOWN,this.OnMouseDown,this),this.node.on(cc.Node.EventType.MOUSE_MOVE,this.OnMouseMove,this),this.node.on(cc.Node.EventType.MOUSE_UP,this.OnMouseUp,this)},t.prototype.onCollisionEnter=function(e,t){e.tag==t.tag&&(this.bb=!0,t.node.x=this.xz.x,t.node.y=this.xz.y,e.node.active=!1,this.ann.active=!1,c.default.qw++)},__decorate([p(cc.Label)],t.prototype,"label",void 0),__decorate([p],t.prototype,"text",void 0),__decorate([p],t.prototype,"mouseb",void 0),__decorate([p(cc.Vec2)],t.prototype,"xz",void 0),__decorate([p],t.prototype,"bb",void 0),__decorate([p(cc.Node)],t.prototype,"ann",void 0),__decorate([p(cc.Vec2)],t.prototype,"xzz",void 0),t=__decorate([n],t)}(cc.Component);o.default=i,cc._RF.pop()},{"./Quanju":"Quanju"}],"碰撞":[function(e,t,o){"use strict";cc._RF.push(t,"75a08oOUpZJPaWR52YMpTP4","碰撞"),Object.defineProperty(o,"__esModule",{value:!0});var c=cc._decorator,r=c.ccclass,n=c.property,p=function(e){function t(){var t=null!==e&&e.apply(this,arguments)||this;return t.label=null,t.text="hello",t}return __extends(t,e),t.prototype.start=function(){cc.director.getCollisionManager().enabled=!0},__decorate([n(cc.Label)],t.prototype,"label",void 0),__decorate([n],t.prototype,"text",void 0),__decorate([n(cc.Vec2)],t.prototype,"xz",void 0),t=__decorate([r],t)}(cc.Component);o.default=p,cc._RF.pop()},{}]},{},["HelloWorld","Quanju","场景","拖动","碰撞"]);