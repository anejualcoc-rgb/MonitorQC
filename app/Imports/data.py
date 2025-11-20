import pandas as pd
from datetime import datetime, timedelta
import random

# Data untuk sheet Produksi
produksi_data = {
    'User': ['Operator A', 'Operator B', 'Operator C', 'Operator A', 'Operator D', 
             'Operator B', 'Operator E', 'Operator C', 'Operator A', 'Operator D',
             'Operator B', 'Operator E', 'Operator A', 'Operator C', 'Operator D'],
    'Tanggal_Produksi': [
        '15/10/2024', '15/10/2024', '15/10/2024',
        '16/10/2024', '16/10/2024', '16/10/2024',
        '17/10/2024', '17/10/2024', '17/10/2024',
        '18/10/2024', '18/10/2024', '18/10/2024',
        '19/10/2024', '19/10/2024', '19/10/2024'
    ],
    'Shift_Produksi': ['Pagi', 'Siang', 'Malam', 'Pagi', 'Siang', 'Malam',
                       'Pagi', 'Siang', 'Malam', 'Pagi', 'Siang', 'Malam',
                       'Pagi', 'Siang', 'Malam'],
    'Line_Produksi': ['Line 1', 'Line 2', 'Line 1', 'Line 1', 'Line 3', 'Line 2',
                      'Line 2', 'Line 1', 'Line 3', 'Line 3', 'Line 2', 'Line 1',
                      'Line 2', 'Line 3', 'Line 1'],
    'Jumlah_Produksi': [850, 920, 780, 950, 880, 830, 1020, 890, 910, 940, 810, 870, 980, 760, 920],
    'Target_Produksi': [1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000]
}

# Data untuk sheet Defect_Major (12 entries)
defect_major_data = {
    'Tanggal_Produksi': [
        '15/10/2024', '15/10/2024', '15/10/2024',
        '16/10/2024', '16/10/2024',
        '17/10/2024', '17/10/2024', '17/10/2024',
        '18/10/2024', '18/10/2024',
        '19/10/2024', '19/10/2024'
    ],
    'Nama_Barang': ['Produk A', 'Produk B', 'Produk C', 
                    'Produk A', 'Produk C',
                    'Produk B', 'Produk D', 'Produk A',
                    'Produk A', 'Produk D',
                    'Produk C', 'Produk B'],
    'Jenis_Defect': ['Bonding Gap', 'Over Cementing', 'Thread Ends',
                     'Dirty/Stain', 'Off Center', 
                     'Bonding Gap', 'Over Cementing', 'Thread Ends',
                     'Dirty/Stain', 'Off Center',
                     'Bonding Gap', 'Over Cementing'],
    'Jumlah_Cacat_perjenis': [15, 8, 10, 12, 5, 10, 7, 9, 7, 9, 6, 8],
    'Severity': ['Major'] * 12
}

# Data untuk sheet Defect_Minor (15 entries)
defect_minor_data = {
    'Tanggal_Produksi': [
        '15/10/2024', '15/10/2024', '15/10/2024',
        '16/10/2024', '16/10/2024', '16/10/2024',
        '17/10/2024', '17/10/2024', '17/10/2024',
        '18/10/2024', '18/10/2024', '18/10/2024',
        '19/10/2024', '19/10/2024', '19/10/2024'
    ],
    'Nama_Barang': ['Produk A', 'Produk B', 'Produk C',
                    'Produk A', 'Produk C', 'Produk D',
                    'Produk B', 'Produk D', 'Produk A',
                    'Produk A', 'Produk C', 'Produk B',
                    'Produk B', 'Produk D', 'Produk A'],
    'Jenis_Defect': ['Bonding Gap', 'Over Cementing', 'Thread Ends',
                     'Dirty/Stain', 'Off Center', 'Bonding Gap',
                     'Over Cementing', 'Thread Ends', 'Dirty/Stain',
                     'Off Center', 'Bonding Gap', 'Over Cementing',
                     'Thread Ends', 'Dirty/Stain', 'Off Center'],
    'Jumlah_Cacat_perjenis': [25, 18, 15, 20, 12, 14, 22, 15, 19, 17, 13, 21, 19, 16, 18],
    'Severity': ['Minor'] * 15
}

# Data untuk sheet Defect_Critical (8 entries)
defect_critical_data = {
    'Tanggal_Produksi': [
        '15/10/2024', '15/10/2024',
        '16/10/2024',
        '17/10/2024', '17/10/2024',
        '18/10/2024',
        '19/10/2024', '19/10/2024'
    ],
    'Nama_Barang': ['Produk A', 'Produk B',
                    'Produk B',
                    'Produk C', 'Produk A',
                    'Produk D',
                    'Produk A', 'Produk C'],
    'Jenis_Defect': ['Bonding Gap', 'Over Cementing',
                     'Thread Ends',
                     'Dirty/Stain', 'Off Center', 
                     'Bonding Gap',
                     'Over Cementing', 'Thread Ends'],
    'Jumlah_Cacat_perjenis': [3, 2, 2, 4, 3, 2, 3, 2],
    'Severity': ['Critical'] * 8
}

# Buat DataFrame
df_produksi = pd.DataFrame(produksi_data)
df_defect_major = pd.DataFrame(defect_major_data)
df_defect_minor = pd.DataFrame(defect_minor_data)
df_defect_critical = pd.DataFrame(defect_critical_data)

# Simpan ke file Excel dengan multiple sheets
output_file = 'Data_Produksi_Import.xlsx'

with pd.ExcelWriter(output_file, engine='openpyxl') as writer:
    df_produksi.to_excel(writer, sheet_name='Produksi', index=False)
    df_defect_major.to_excel(writer, sheet_name='Defect_Major', index=False)
    df_defect_minor.to_excel(writer, sheet_name='Defect_Minor', index=False)
    df_defect_critical.to_excel(writer, sheet_name='Defect_Critical', index=False)

print(f"✓ File Excel berhasil dibuat: {output_file}")
print("\nRingkasan Data:")
print(f"- Sheet 'Produksi': {len(df_produksi)} baris data")
print(f"- Sheet 'Defect_Major': {len(df_defect_major)} baris data")
print(f"- Sheet 'Defect_Minor': {len(df_defect_minor)} baris data")
print(f"- Sheet 'Defect_Critical': {len(df_defect_critical)} baris data")
print("\nTotal defect per tanggal:")
for date in df_produksi['Tanggal_Produksi'].unique():
    total = (
        df_defect_major[df_defect_major['Tanggal_Produksi'] == date]['Jumlah_Cacat_perjenis'].sum() +
        df_defect_minor[df_defect_minor['Tanggal_Produksi'] == date]['Jumlah_Cacat_perjenis'].sum() +
        df_defect_critical[df_defect_critical['Tanggal_Produksi'] == date]['Jumlah_Cacat_perjenis'].sum()
    )
    print(f"  {date}: {total} unit cacat")