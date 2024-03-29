<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\CouponStatus;
use App\Models\Pendingcoupon;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PendingcouponResource\Pages;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use App\Filament\Resources\PendingcouponResource\RelationManagers;
use Laravel\SerializableClosure\Serializers\Native;

class PendingcouponResource extends Resource
{
    protected static ?string $model = Pendingcoupon::class;

    protected static ?string $navigationIcon = 'tabler-progress-check';
    protected static ?string $modelLabel = 'elbírálásra váró kupon';
    protected static ?string $pluralModelLabel = 'elbírálásra váró kuponok';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('coupon_code')
                    ->label('Kuponkód')
                    ->description(fn (Pendingcoupon $record): string => $record->source)
                    ->wrap()
                    ->color('Amber')
                    ->searchable(),
                TextColumn::make('adult')
                    ->label('Utasok')
                    ->formatStateUsing(function ($state, Pendingcoupon $payload) {
                        return '<p style="color:gray; font-size:9pt;"><b style="color:white; font-size:11pt; font-weight:normal;">' . $payload->adult . '</b> felnőtt</p><p style="color:gray; font-size:9pt;"><b style="color:white; font-size:11pt; font-weight:normal;">' . $payload->children . '</b> gyerek</p>';
                    })->html()
                    ->searchable(),
                TextColumn::make('vip')
                    ->label(false)
                    ->badge()
                    ->width(30)
                    ->size('sm'),
                TextColumn::make('private')
                    ->label(false)
                    ->badge()
                    ->size('sm'),
                SelectColumn::make('status')
                ->options([
                    CouponStatus::UnderProcess->value => CouponStatus::UnderProcess->getSelectLabel(),
                    CouponStatus::CanBeUsed->value => CouponStatus::CanBeUsed->getSelectLabel(),
                ])
                ->selectablePlaceholder(false),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->underProcess();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendingcoupons::route('/'),
            'create' => Pages\CreatePendingcoupon::route('/create'),
            'edit' => Pages\EditPendingcoupon::route('/{record}/edit'),
        ];
    }
}
