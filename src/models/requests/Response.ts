export default interface Response<T> {
  data: T;
  message: string;
  status: 'ok' | 'error';
}