import request from '@/utils/rest';

export function getUploadURLKPI(yearperiod, deptid, subcode, level) {
  if (!yearperiod) return '';
  if (!deptid) return '';
  if (!subcode) return '';
  if (!level) return '';
  return process.env.VUE_APP_BASE_URL + '/kpidept_upload_kpi/' +
    yearperiod.toString() + '/' +
    deptid + '/' + subcode + '/' + level.toString();;
}


export function getUploadURLML(yearperiod, deptid, subcode, level) {
  if (!yearperiod) return '';
  if (!deptid) return '';
  if (!subcode) return '';
  if (!level) return '';
  return process.env.VUE_APP_BASE_URL + '/kpidept_upload_ml/' +
    yearperiod.toString() + '/' +
    deptid + '/' + subcode  + '/' + level.toString();;
}

export function getUploadLog() {
  return request({
    url: 'uploadlog',
    method: 'get'
  })
}
