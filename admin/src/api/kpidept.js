import request from '@/utils/rest';

export function getUploadURL(yearperiod, deptcode, subcode) {
  // console.log(process.env.VUE_APP_BASE_API);
  if (!yearperiod) return '';
  if (!deptcode) return '';
  if (!subcode) return '';

  deptcode = deptcode.replace('.', '_');
  subcode = subcode.replace('.', '_');

  return process.env.VUE_APP_BASE_URL + '/kpidept_upload/' +
    yearperiod.toString() + '/' +
    deptcode + '/' + subcode;
}

export function getUploadLog() {
  return request({
    url: 'uploadlog',
    method: 'get'
  })
}
