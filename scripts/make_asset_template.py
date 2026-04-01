import openpyxl
from openpyxl.styles import Font

wb = openpyxl.Workbook()
ws = wb.active
ws.title = 'Asset Template'
headers = ['kode_aset','nama','tipe','merek','model','nomor_seri','spesifikasi','lokasi','pemegang','status','kondisi','tanggal_dibeli']
ws.append(headers)
ws.append([
    'ASSET-0001',
    'Laptop Dell Vostro 3510',
    'Laptop',
    'Dell',
    'Vostro 3510',
    'SN123456',
    '{"cpu":"i5","ram":"16GB","storage":"512GB SSD"}',
    'Ruang Server',
    'TI',
    'ACTIVE',
    'GOOD',
    '2026-04-01',
])
for cell in ws[1]:
    cell.font = Font(bold=True)
wb.save('asset-upload-template.xlsx')
print('asset-upload-template.xlsx created')
