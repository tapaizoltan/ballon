<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pilot;
use App\Models\Aircraft;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;

/* saját use-ok */
use App\Models\AircraftLocationPilot;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AircraftLocationPilotResource\Pages;
use App\Filament\Resources\AircraftLocationPilotResource\RelationManagers;

class AircraftLocationPilotResource extends Resource
{
    protected static ?string $model = AircraftLocationPilot::class;

    protected static ?string $navigationIcon = 'iconoir-database-script';
    protected static ?string $modelLabel = 'repülési terv';
    protected static ?string $pluralModelLabel = 'repülési tervek';

    protected static ?string $navigationGroup = 'Alapadatok';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(4)
                ->schema([
                    Section::make() 
                    ->schema([
                        Forms\Components\Fieldset::make('Tervezett repülés')
                        ->schema([
                            Forms\Components\DatePicker::make('date')
                                /*->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Adjon egy fantázianevet a légijárműnek. Érdemes olyan nevet választani, amivel könnyedén azonosítható lesz az adott légijármű.')*/
                                /*->helperText('Adjon egy fantázianevet a helyszínnek. Érdemes olyan nevet választani, amivel könnyedén azonosítható lesz az adott helyszín.')*/
                                ->label('Dátum')
                                ->prefixIcon('tabler-calendar')
                                ->weekStartsOnMonday()
                                //->placeholder(now())
                                ->displayFormat('Y-m-d')
                                ->native(false),

                            Forms\Components\TimePicker::make('time')
                                /*->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Ide a légijármű lajstromjelét adja meg.')*/
                                /*->helperText('Ide a légijármű lajstromjelét adja meg.')*/
                                ->label('Időpont')
                                ->prefixIcon('tabler-clock')
                                //->placeholder(now())
                                ->displayFormat('H:i:s')
                                ->native(false),
                            ])->columns(2),
                        ])->columnSpan(3),
                ]),

                Grid::make(4)
                ->schema([
                    Section::make() 
                    ->schema([
                        Forms\Components\Fieldset::make('Kiírás')
                        ->schema([
                            Forms\Components\Select::make('aircraft_id')
                                /*->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Ide a légijármű lajstromjelét adja meg.')*/
                                /*->helperText('Ide a légijármű lajstromjelét adja meg.')*/
                                ->label('Légijármű')
                                ->prefixIcon('tabler-ufo')
                                ->options(Aircraft::all()->pluck('name', 'id'))
                                ->native(false)
                                ->searchable(),

                            Forms\Components\Select::make('location_id')
                                ->label('Helyszín')
                                ->prefixIcon('iconoir-strategy')
                                ->options(Location::all()->pluck('name', 'id'))
                                ->native(false)
                                ->searchable(),

                            Forms\Components\Select::make('pilot_id')
                                /*->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Ide a légijármű lajstromjelét adja meg.')*/
                                /*->helperText('Ide a légijármű lajstromjelét adja meg.')*/
                                ->label('Pilóta')
                                ->prefixIcon('iconoir-user-square')
                                ->options(Pilot::all()->pluck('fullname', 'id')) // <-ez egy modell szinten deklarált atribútum
                                ->native(false)
                                ->searchable(),
                            ])->columns(3),

                        ])->columnSpan(3),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->label('Dátum')->searchable(),
                Tables\Columns\TextColumn::make('time')->label('Időpont')->searchable(),
                Tables\Columns\TextColumn::make('aircraft_id')->label('Légijármű')->searchable()
                ->formatStateUsing(function ($state, AircraftLocationPilot $aircraft_localtion_pilot) {
                    $aircraft_name = Aircraft::find($aircraft_localtion_pilot->aircraft_id);
                    return $aircraft_name->name;
                }),
                Tables\Columns\TextColumn::make('location_id')->label('Helyszín')->searchable()
                ->formatStateUsing(function ($state, AircraftLocationPilot $aircraft_localtion_pilot) {
                    $location_name = Location::find($aircraft_localtion_pilot->location_id);
                    return $location_name->name;
                }),
                Tables\Columns\TextColumn::make('pilot.fullname')->label('Pilóta')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Megtekintés')->link(),
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Szerkesztés')->link(),
                Tables\Actions\Action::make('delete')->icon('heroicon-m-trash')->color('danger')->hiddenLabel()->tooltip('Törlés')->link()->requiresConfirmation()->action(fn ($record) => $record->delete()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Mind törlése'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAircraftLocationPilots::route('/'),
            'create' => Pages\CreateAircraftLocationPilot::route('/create'),
            /*'view' => Pages\ViewAircraftLocationPilot::route('/{record}'),*/
            'edit' => Pages\EditAircraftLocationPilot::route('/{record}/edit'),
        ];
    }
}
