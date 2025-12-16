# Stage 1: build
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /app

# Copier tout le projet
COPY . .

# Restaurer les dépendances et publier
RUN dotnet restore
RUN dotnet publish -c Release -o out

# Stage 2: runtime
FROM mcr.microsoft.com/dotnet/aspnet:8.0
WORKDIR /app

# Copier les fichiers publiés depuis le build
COPY --from=build /app/out .

# Lancer l'application
ENTRYPOINT ["dotnet", "gescommandeCsharp.dll"]
