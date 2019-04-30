import {
  SET_STRUCTURES, SET_CURRENT, SET_CONFIG, ADD_STRUCTURE, MOD_STRUCTURE, SET_CONFIG_LOADED

 } from "../constants/action-types";


export function setStructures(payload) {
  return { type: SET_STRUCTURES, payload };
};

export function setConfig(payload) {
  return { type: SET_CONFIG, payload };
};

export function setCurrent(payload) {
  return { type: SET_CURRENT, payload };
};

export function addStructure(payload) {
  return { type: ADD_STRUCTURE, payload };
};

export function modStructure(payload) {
  return { type: MOD_STRUCTURE, payload };
};

export function setConfigLoaded(payload) {
  return { type: SET_CONFIG_LOADED, payload };
};




