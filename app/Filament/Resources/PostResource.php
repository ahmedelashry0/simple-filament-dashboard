<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->label('Category'),
                        TextInput::make('title')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->required()
                            ->maxLength(255)
                            ->label('Post Title'),
                        TextInput::make('slug')->required(),
                        SpatieMediaLibraryFileUpload::make('thumbnail')->collection('posts'),
                        RichEditor::make('content')
                            ->required()
                            ->label('Content'),

                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(false)
                            ->inline()
                            ->required()
                    ]
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('thumbnail')->collection('posts')
                    ->label('Thumbnail')
                    ->conversion('thumb')
                    ->circular()
                    ->size(50),
                IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published')
            ])
            ->filters([
                Tables\Filters\Filter::make('published')
                    ->query(fn($query) => $query->where('is_published', true))
                    ->toggle()
                    ->label('Published'),
                Tables\Filters\Filter::make('Unpublished')
                    ->query(fn($query) => $query->where('is_published', false))
                    ->toggle()
                    ->label('UnPublished'),
                SelectFilter::make('category')
                    ->relationship('category', 'name')
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
            RelationManagers\TagsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            PostResource\Widgets\StatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
