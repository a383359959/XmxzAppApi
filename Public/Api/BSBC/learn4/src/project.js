require=function c(t,n,a){function e(o,s){if(!n[o]){if(!t[o]){var r="function"==typeof require&&require;if(!s&&r)return r(o,!0);if(i)return i(o,!0);var p=new Error("Cannot find module '"+o+"'");throw p.code="MODULE_NOT_FOUND",p}var _=n[o]={exports:{}};t[o][0].call(_.exports,function(c){var n=t[o][1][c];return e(n||c)},_,_.exports,c,t,n,a)}return n[o].exports}for(var i="function"==typeof require&&require,o=0;o<a.length;o++)e(a[o]);return e}({Home:[function(c,t,n){"use strict";cc._RF.push(t,"dee09vBZ8pHqrY2Lj3gqVKW","Home"),cc.Class({extends:cc.Component,properties:{},start:function(){var c=cc.find("Canvas/player_box/play_btn"),t=cc.find("Canvas/player_box/stop_btn");c.active=!0,t.active=!1;var n=cc.find("Canvas/B_bg"),a=cc.find("Canvas/A_1_bg"),e=cc.find("Canvas/A_2_bg");n.active=!0,a.active=!1,e.active=!1}}),cc._RF.pop()},{}],btn_A:[function(c,t,n){"use strict";cc._RF.push(t,"555c6unyHJD44ID5mHOBn53","btn_A"),cc.Class({extends:cc.Component,properties:{},start:function(){var c=0;this.node.on("touchstart",function(){var t=cc.find("Canvas/B_bg"),n=cc.find("Canvas/A_1_bg"),a=cc.find("Canvas/A_2_bg");1==t.active?(c=0,t.active=!1,n.active=!0,a.active=!1):(t.active=!1,c%2==0?(n.active=!1,a.active=!0):(n.active=!0,a.active=!1),c++)})}}),cc._RF.pop()},{}],btn_B:[function(c,t,n){"use strict";cc._RF.push(t,"bd092mJBbdCqaikRi7DZ2hp","btn_B"),cc.Class({extends:cc.Component,properties:{},start:function(){this.node.on("touchstart",function(){var c=cc.find("Canvas/B_bg"),t=cc.find("Canvas/A_1_bg"),n=cc.find("Canvas/A_2_bg");c.active=!0,t.active=!1,n.active=!1})}}),cc._RF.pop()},{}],play_btn:[function(c,t,n){"use strict";cc._RF.push(t,"6bb94qhLPhBVqW1TJWEsH9H","play_btn"),cc.Class({extends:cc.Component,properties:{},start:function(){this.node.on("touchstart",function(){var c=cc.find("Canvas/player_box/play_btn"),t=cc.find("Canvas/player_box/stop_btn"),n=cc.find("Canvas/player_box/music").getComponent(cc.AudioSource);c.active=!1,t.active=!0,n.play()})}}),cc._RF.pop()},{}],stop_btn:[function(c,t,n){"use strict";cc._RF.push(t,"53e74Z7DPhLI6aaivkMLi7e","stop_btn"),cc.Class({extends:cc.Component,properties:{},start:function(){this.node.on("touchstart",function(){var c=cc.find("Canvas/player_box/play_btn"),t=cc.find("Canvas/player_box/stop_btn"),n=cc.find("Canvas/player_box/music").getComponent(cc.AudioSource);c.active=!0,t.active=!1,n.stop()})}}),cc._RF.pop()},{}]},{},["Home","btn_A","btn_B","play_btn","stop_btn"]);