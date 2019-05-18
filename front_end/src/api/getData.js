import fetch from '../config/fetch'

/**
 * 登录
 */

export const login = data => fetch('/Login/login', 'post', data);

/**
 * 检查是否登录
 */

export const checkLogin = () => fetch('/Login/checkLogin', 'get');

/**
 * 注销用户
 */

export const logout = () => fetch('/Login/logout', 'get');

/**
 * 获取case聊天记录
 */

export const getCaseMsg = data => fetch('/CaseController/getCaseMsg', 'post', data);

/**
 * 根据筛选条件获取case数
 * @param data
 * @returns {Promise<*>}
 */

export const getFilterCount = data => fetch('/CaseController/filterCount', 'post', data);

/**
 * 获取被质检团队列表
 * @returns {Promise<*>}
 */

export const getBeTestTeam = () => fetch('/CaseController/getBeTestTeam', 'get');

/**
 * 获取质检员列表
 * @returns {Promise<*>}
 */

export const getTestWorker = () => fetch('/CaseController/getTestWorker','get');

/**
 * 获取创建人列表
 * @returns {Promise<*>}
 */

export const getCreater = () => fetch('/CaseController/getCreater','get');

/**
 * 获取质检单列表
 * @param data
 * @returns {Promise<*>}
 */

export const getCaseData = data => fetch('/CaseController/getCaseData', 'post', data);

/**
 * 检查质检员权限
 * @param data
 * @returns {Promise<*>}
 */

export const checkPrivilege = data => fetch('/CaseController/checkPrivilege', 'post', data);

/**
 * 给质检单打分
 * @param data
 * @returns {Promise<*>}
 */

export const setGrade = data => fetch('/CaseController/markCase', 'post', data);

/**
 * 分配质检单
 * @param data
 * @returns {Promise<*>}
 */

export const filterHandout = data => fetch('/CaseController/filterHandout', 'post', data);

/**
 * 导出excel
 * @param data
 * @returns {Promise<*>}
 */

export const exportExcel = data => fetch('/CaseController/exportExcel', 'post', data);